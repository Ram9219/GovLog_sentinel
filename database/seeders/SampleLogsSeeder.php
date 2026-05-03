<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\LogCreationService;
use Carbon\Carbon;

class SampleLogsSeeder extends Seeder
{
    public function run(): void
    {
        $logService = app(LogCreationService::class);
        $now = Carbon::now();

        // =====================================================================
        // E-GOVERNANCE REALISTIC LOG SCENARIOS
        // =====================================================================

        $scenarios = [
            // === AUTHENTICATION EVENTS ===
            ['action_type' => 'user_login', 'message' => 'User admin@aicte.gov.in logged in successfully', 'severity' => 'info', 'classification' => 'authentication', 'source_ip' => '192.168.1.10', 'context' => ['browser' => 'Chrome 120', 'os' => 'Windows 11']],
            ['action_type' => 'user_login', 'message' => 'User registrar@college.edu.in logged in successfully', 'severity' => 'info', 'classification' => 'authentication', 'source_ip' => '10.0.0.25', 'context' => ['browser' => 'Firefox 121', 'os' => 'Ubuntu 22.04']],
            ['action_type' => 'failed_login', 'message' => 'Failed login attempt for admin@aicte.gov.in — wrong password', 'severity' => 'warning', 'classification' => 'authentication', 'source_ip' => '203.0.113.45', 'context' => ['attempts' => 3]],
            ['action_type' => 'failed_login', 'message' => 'Failed login attempt for unknown@hacker.com — user not found', 'severity' => 'warning', 'classification' => 'authentication', 'source_ip' => '198.51.100.77', 'context' => ['attempts' => 1]],
            ['action_type' => 'brute_force_detected', 'message' => 'CRITICAL: 25 failed login attempts detected from IP 203.0.113.45 in 5 minutes', 'severity' => 'critical', 'classification' => 'security_breach', 'source_ip' => '203.0.113.45', 'context' => ['attempts' => 25, 'timeframe' => '5 minutes', 'blocked' => true]],
            ['action_type' => 'user_logout', 'message' => 'User admin@aicte.gov.in logged out', 'severity' => 'info', 'classification' => 'authentication', 'source_ip' => '192.168.1.10', 'context' => []],

            // === STUDENT MANAGEMENT ===
            ['action_type' => 'student_create', 'message' => 'New student record created: STU-2026-0451 (Rahul Sharma)', 'severity' => 'info', 'classification' => 'student_management', 'source_ip' => '10.0.0.25', 'context' => ['student_id' => 'STU-2026-0451', 'department' => 'Computer Science']],
            ['action_type' => 'student_update', 'message' => 'Student record updated: STU-2026-0312 — marks corrected', 'severity' => 'info', 'classification' => 'student_management', 'source_ip' => '10.0.0.25', 'context' => ['student_id' => 'STU-2026-0312', 'field_changed' => 'marks']],
            ['action_type' => 'student_delete', 'message' => 'Student record DELETED: STU-2025-0198 — reason: duplicate entry', 'severity' => 'warning', 'classification' => 'student_management', 'source_ip' => '10.0.0.25', 'context' => ['student_id' => 'STU-2025-0198', 'reason' => 'duplicate']],
            ['action_type' => 'bulk_student_import', 'message' => '245 student records imported from CSV upload', 'severity' => 'info', 'classification' => 'student_management', 'source_ip' => '10.0.0.25', 'context' => ['count' => 245, 'file' => 'students_batch_2026.csv']],

            // === DATA & SECURITY ===
            ['action_type' => 'data_export', 'message' => 'ALERT: Bulk data export — 10,000 student records exported to CSV', 'severity' => 'critical', 'classification' => 'data_breach', 'source_ip' => '203.0.113.45', 'context' => ['records' => 10000, 'export_type' => 'csv']],
            ['action_type' => 'unauthorized_access', 'message' => 'Unauthorized API access attempt to /api/admin/config from external IP', 'severity' => 'critical', 'classification' => 'security_breach', 'source_ip' => '198.51.100.23', 'context' => ['endpoint' => '/api/admin/config', 'method' => 'GET']],
            ['action_type' => 'sql_injection_attempt', 'message' => 'SQL injection pattern detected in search query parameter', 'severity' => 'critical', 'classification' => 'security_breach', 'source_ip' => '203.0.113.99', 'context' => ['parameter' => 'search', 'pattern' => "' OR 1=1 --"]],
            ['action_type' => 'file_upload', 'message' => 'Suspicious file upload blocked: malware.exe.pdf', 'severity' => 'critical', 'classification' => 'security_breach', 'source_ip' => '198.51.100.42', 'context' => ['filename' => 'malware.exe.pdf', 'blocked' => true]],

            // === DATABASE EVENTS ===
            ['action_type' => 'database_backup', 'message' => 'Automated database backup completed: govlog_sentinel_20260503.sql.gz (245MB)', 'severity' => 'info', 'classification' => 'database', 'source_ip' => '127.0.0.1', 'context' => ['size' => '245MB', 'duration' => '12s']],
            ['action_type' => 'database_error', 'message' => 'Database connection timeout after 30 seconds on query to student_records', 'severity' => 'error', 'classification' => 'database', 'source_ip' => '127.0.0.1', 'context' => ['table' => 'student_records', 'timeout' => 30]],
            ['action_type' => 'database_migration', 'message' => 'Database migration executed: add_role_column_to_users', 'severity' => 'info', 'classification' => 'database', 'source_ip' => '127.0.0.1', 'context' => ['migration' => 'add_role_column_to_users']],

            // === SYSTEM MONITORING ===
            ['action_type' => 'system_health', 'message' => 'System health check: CPU 15%, Memory 2.4GB/8GB, Disk 45%', 'severity' => 'info', 'classification' => 'system', 'source_ip' => '127.0.0.1', 'context' => ['cpu' => '15%', 'memory' => '2.4GB/8GB', 'disk' => '45%']],
            ['action_type' => 'system_health', 'message' => 'WARNING: Disk usage at 85% — approaching capacity', 'severity' => 'warning', 'classification' => 'system', 'source_ip' => '127.0.0.1', 'context' => ['disk' => '85%', 'threshold' => '80%']],
            ['action_type' => 'system_error', 'message' => 'ERROR: Mail queue backed up — 150 emails pending delivery', 'severity' => 'error', 'classification' => 'system', 'source_ip' => '127.0.0.1', 'context' => ['queue_size' => 150]],
            ['action_type' => 'system_restart', 'message' => 'Application server restarted after scheduled maintenance', 'severity' => 'info', 'classification' => 'system', 'source_ip' => '127.0.0.1', 'context' => ['downtime' => '2 minutes']],

            // === COMPLIANCE & AUDIT ===
            ['action_type' => 'compliance_report', 'message' => 'Monthly AICTE compliance report generated: AICTE-RPT-2026-05', 'severity' => 'info', 'classification' => 'compliance', 'source_ip' => '192.168.1.10', 'context' => ['report_id' => 'AICTE-RPT-2026-05', 'records' => 1543]],
            ['action_type' => 'audit_trail_export', 'message' => 'Audit trail exported for external review — records: Jan-Apr 2026', 'severity' => 'info', 'classification' => 'compliance', 'source_ip' => '192.168.1.10', 'context' => ['period' => 'Jan-Apr 2026']],
            ['action_type' => 'config_change', 'message' => 'Admin changed notification settings: email alerts enabled for critical events', 'severity' => 'warning', 'classification' => 'configuration', 'source_ip' => '192.168.1.10', 'context' => ['setting' => 'critical_email_alerts', 'value' => true]],

            // === FILE & API OPERATIONS ===
            ['action_type' => 'file_access', 'message' => 'Exam results PDF downloaded: results_CSE_sem6_2026.pdf', 'severity' => 'info', 'classification' => 'file_access', 'source_ip' => '172.16.0.5', 'context' => ['file' => 'results_CSE_sem6_2026.pdf']],
            ['action_type' => 'api_call', 'message' => 'External API call to AICTE portal for institution verification', 'severity' => 'info', 'classification' => 'api_integration', 'source_ip' => '127.0.0.1', 'context' => ['endpoint' => 'https://api.aicte-india.org/verify', 'status' => 200]],
            ['action_type' => 'api_error', 'message' => 'ERROR: AICTE API returned 503 — service temporarily unavailable', 'severity' => 'error', 'classification' => 'api_integration', 'source_ip' => '127.0.0.1', 'context' => ['status' => 503, 'retry_after' => 60]],
        ];

        // Create the scenario logs with timestamps spread over 7 days
        foreach ($scenarios as $i => $scenario) {
            $scenario['timestamp'] = $now->copy()->subDays(rand(0, 6))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $logService->create($scenario);
        }

        // Generate 150+ random realistic logs spread over 30 days
        $severities = ['info', 'info', 'info', 'info', 'warning', 'warning', 'error', 'critical'];
        $classifications = ['authentication', 'database', 'file_access', 'student_management', 'system', 'compliance', 'security_breach', 'api_integration', 'configuration'];
        $actions = ['user_login', 'page_view', 'file_upload', 'record_update', 'api_call', 'config_change', 'system_health', 'database_query', 'student_update', 'report_generate'];
        $ips = ['192.168.1.10', '192.168.1.15', '10.0.0.1', '10.0.0.25', '172.16.0.5', '203.0.113.45', '127.0.0.1', '192.168.1.100'];

        $messages = [
            'User viewed dashboard page',
            'Student attendance report generated for CSE department',
            'Faculty profile updated — Dr. Priya Nair',
            'Exam schedule published for semester 6',
            'Course registration opened for AY 2026-27',
            'Payment gateway callback received — transaction TXN-20260503',
            'Library book issue recorded — ISBN 978-0-13-468599-1',
            'Placement drive data uploaded — 45 companies',
            'HOD approved leave request for faculty ID FAC-0234',
            'Timetable collision detected — Room 301, Slot 3',
            'Fee defaulter report generated — 23 students flagged',
            'Scholarship eligibility check completed for 156 students',
            'Hostel room allocation updated for Block A',
            'Lab equipment inventory sync completed',
            'Internal assessment marks uploaded for ME301',
            'Admit card generation batch started — 450 students',
            'Grade card PDF generated for STU-2026-0312',
            'Alumni database sync with LinkedIn API',
            'NAAC data collection automated report triggered',
            'Server response time exceeded 2s threshold on /api/students',
        ];

        for ($i = 0; $i < 150; $i++) {
            $severity = $severities[array_rand($severities)];
            $logService->create([
                'action_type' => $actions[array_rand($actions)],
                'message' => $messages[array_rand($messages)],
                'severity' => $severity,
                'classification' => $classifications[array_rand($classifications)],
                'source_ip' => $ips[array_rand($ips)],
                'timestamp' => $now->copy()->subDays(rand(0, 29))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                'context' => [
                    'session_id' => 'sess_' . bin2hex(random_bytes(8)),
                    'request_duration_ms' => rand(15, 2500),
                ],
            ]);
        }

        $this->command->info('✅ Created ' . (count($scenarios) + 150) . ' realistic e-governance logs!');
    }
}