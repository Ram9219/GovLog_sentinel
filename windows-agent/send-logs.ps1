$ErrorActionPreference = 'Stop'

$ApiUrl = $env:GOVLOG_API_URL
$AgentToken = $env:LOG_AGENT_TOKEN
$PollIntervalSeconds = if ([string]::IsNullOrWhiteSpace($env:GOVLOG_POLL_SECONDS)) { 15 } else { [int]$env:GOVLOG_POLL_SECONDS }
$StateFile = Join-Path $PSScriptRoot 'state.json'
$LogsToWatch = @('System', 'Application')
$ComputerName = $env:COMPUTERNAME

if ([string]::IsNullOrWhiteSpace($ApiUrl)) {
    throw 'Set GOVLOG_API_URL, for example: http://127.0.0.1:8000/api/agent/logs'
}

if ([string]::IsNullOrWhiteSpace($AgentToken)) {
    throw 'Set LOG_AGENT_TOKEN to match the Laravel server configuration.'
}

function Get-State {
    if (Test-Path $StateFile) {
        try {
            return Get-Content $StateFile -Raw | ConvertFrom-Json
        } catch {
            return [pscustomobject]@{}
        }
    }

    return [pscustomobject]@{}
}

function Save-State {
    param([Parameter(Mandatory = $true)]$State)
    $State | ConvertTo-Json -Depth 10 | Set-Content -Path $StateFile -Encoding UTF8
}

function Send-GovLog {
    param(
        [string]$ActionType,
        [string]$Message,
        [string]$Severity = 'info',
        [string]$Classification = 'system',
        [hashtable]$Context = @{},
        [hashtable]$Metadata = @{},
        [string]$SourceIp = '127.0.0.1'
    )

    $payload = @{
        action_type    = $ActionType
        message        = $Message
        severity       = $Severity
        classification = $Classification
        source_ip      = $SourceIp
        context        = $Context
        metadata       = $Metadata
        request_id     = [guid]::NewGuid().ToString()
        timestamp      = (Get-Date).ToString('o')
    } | ConvertTo-Json -Depth 10

    Invoke-RestMethod -Method Post -Uri $ApiUrl -Headers @{ 'X-Agent-Token' = $AgentToken } -ContentType 'application/json' -Body $payload | Out-Null
}

function Get-EventSeverity {
    param([System.Diagnostics.Eventing.Reader.EventRecord]$Event)

    switch ($Event.LevelDisplayName) {
        'Critical' { 'critical' }
        'Error'    { 'error' }
        'Warning'  { 'warning' }
        default    { 'info' }
    }
}

function Get-EventClassification {
    param([string]$LogName, [System.Diagnostics.Eventing.Reader.EventRecord]$Event)

    if ($LogName -eq 'Security') {
        return 'security_breach'
    }

    if ($Event.Id -in 4624, 4625) {
        return 'authentication'
    }

    if ($Event.LevelDisplayName -in @('Error', 'Critical')) {
        return 'system'
    }

    return 'system'
}

function Get-EventActionType {
    param([System.Diagnostics.Eventing.Reader.EventRecord]$Event)

    switch ($Event.Id) {
        4624 { 'windows_login' }
        4625 { 'windows_failed_login' }
        1102 { 'event_log_cleared' }
        7040 { 'service_start_type_changed' }
        default { 'windows_event' }
    }
}

function Get-EventMessage {
    param([System.Diagnostics.Eventing.Reader.EventRecord]$Event)
    try {
        return $Event.FormatDescription()
    } catch {
        return $Event.ToXml()
    }
}

function Get-NewEvents {
    param(
        [string]$LogName,
        [int]$LastRecordId
    )

    $filter = @{
        LogName = $LogName
        StartTime = (Get-Date).AddMinutes(-10)
    }

    $events = Get-WinEvent -FilterHashtable $filter -ErrorAction SilentlyContinue |
        Sort-Object RecordId |
        Where-Object { $_.RecordId -gt $LastRecordId }

    return $events
}

$state = Get-State

foreach ($logName in $LogsToWatch) {
    if (-not ($state.PSObject.Properties.Name -contains $logName)) {
        $state | Add-Member -NotePropertyName $logName -NotePropertyValue 0
    }
}

Write-Host "[GovLog] Watching Windows logs on $ComputerName ..."

while ($true) {
    foreach ($logName in $LogsToWatch) {
        $currentStateValue = $state.PSObject.Properties[$logName]
        $lastRecordId = if ($null -eq $currentStateValue) { 0 } else { [int]$currentStateValue.Value }
        $newEvents = Get-NewEvents -LogName $logName -LastRecordId $lastRecordId

        foreach ($event in $newEvents) {
            $message = Get-EventMessage -Event $event
            $severity = Get-EventSeverity -Event $event
            $classification = Get-EventClassification -LogName $logName -Event $event
            $actionType = Get-EventActionType -Event $event

            if ([string]::IsNullOrWhiteSpace($message)) {
                $message = "$logName event $($event.Id)"
            }

            Send-GovLog -ActionType $actionType -Message $message -Severity $severity -Classification $classification -Context @{
                log_name = $logName
                event_id = $event.Id
                record_id = $event.RecordId
                provider = $event.ProviderName
                machine_name = $event.MachineName
                level = $event.LevelDisplayName
            } -Metadata @{
                computer_name = $ComputerName
                source = 'windows-event-log-agent'
            } -SourceIp '127.0.0.1'

            $state.$logName = [int]$event.RecordId
        }
    }

    Save-State -State $state
    Start-Sleep -Seconds $PollIntervalSeconds
}
