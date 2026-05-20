# Critical Alert Demo — Step by Step

## Step 1: Seed the Database with Classification Rules & Sample Logs

Run this command in the terminal to populate the database with pre-defined classification rules and sample logs:

```bash
php artisan db:seed
```

This will:
- Create seeded admin/operator/viewer users
- Insert classification rules (like "data breach", "unauthorized access", etc.)
- Insert 100+ sample logs (including some critical ones)

**What happens:** The database now has rules that mark logs as "critical" when they contain keywords like:
- "unauthorized access"
- "data leak"
- "breach"
- "suspicious export"
- "policy violation"
- "compliance failure"

---

## Step 2: Log in to the Admin Dashboard

1. Go to `http://localhost/login`
2. Enter:
   - **Email:** `admin@govlog.in`
   - **Role:** Select "Admin" from the dropdown
   - **Password:** `admin@123`
3. Click "Sign In"

You are now logged in as an admin.

---

## Step 3: Create a Test Critical Log Manually

Open a new terminal and run:

```bash
php artisan tinker
```

This opens the Laravel interactive shell. Now paste this code:

```php
$logService = app(\App\Services\LogCreationService::class);

$log = $logService->create([
    'action_type' => 'unauthorized_api_access',
    'message' => 'ALERT: Unauthorized access attempt detected from external IP 203.0.113.45 trying to access /api/admin/config',
    'severity' => 'critical',
    'classification' => 'security_breach',
    'source_ip' => '203.0.113.45',
    'context' => [
        'endpoint' => '/api/admin/config',
        'method' => 'GET',
        'attempts' => 5
    ]
]);

echo "Critical log created with ID: " . $log->id;
```

Press Enter and you should see: **"Critical log created with ID: [number]"**

**What just happened:**
- A new log was created with severity "critical"
- The LogCreationService automatically detected it's critical
- Notification listeners were triggered (in background queue)
- The log is now in the database and ready to display

---

## Step 4: View the Critical Alert on the Dashboard

1. Stay on the admin dashboard page
2. Click the **"Critical Alerts"** link in the navigation bar
3. You should now see your new critical log at the top of the list with:
   - Red "CRITICAL" badge
   - The message: "ALERT: Unauthorized access attempt detected..."
   - Timestamp, IP address, action type, and a "View Details" button

---

## Step 5: View the Alert Details

Click **"View Details →"** on your critical log entry to see:
- Full message
- Source IP: `203.0.113.45`
- Classification: `security_breach`
- Severity: `CRITICAL`
- The context (endpoint, method, attempts)
- Notification status (whether it was sent)

---

## Step 6: Show the Code That Triggered the Alert (In Tinker)

Back in the tinker terminal, run:

```php
$log = \App\Models\ServerLog::latest()->first();
dd($log->toArray());
```

This shows the exact log record saved in the database, proving the critical alert exists and was stored.

---

## Step 7: Prove the Notification Was Sent

Still in tinker, run:

```php
$log = \App\Models\ServerLog::latest()->first();
echo "Is Notified: " . ($log->is_notified ? "YES" : "NO");
echo "\nNotification Results: ";
dd($log->notification_results);
```

This shows:
- Whether the notification was marked as sent
- Which channels were used (email, SMS, WhatsApp, database, Pusher)
- Whether each channel succeeded or failed

---

## Alternative: Auto-Trigger a Critical Log Using a Sample Message

If you want to test without manually coding, run this in tinker:

```php
$logService = app(\App\Services\LogCreationService::class);

// Create a log with a message containing a critical keyword
$log = $logService->create([
    'action_type' => 'suspicious_activity',
    'message' => 'Suspicious export of 5000 student records detected from user admin@test.com at 2026-05-04 15:32:10',
    'source_ip' => '192.168.1.50',
    'context' => ['records_exported' => 5000]
]);

echo "Log created. Severity: " . $log->severity;
```

The classifier will automatically detect the word "suspicious export" and mark it as **critical**.

---

## Step 8: Show the Classification Rules (Code Proof)

Open the file: `database/seeders/ClassificationRulesSeeder.php`

Show your teacher the rules that define what makes a log critical:

```php
[
    'name' => 'Data Breach Detection',
    'classification' => 'data_breach',
    'severity' => 'critical',
    'patterns' => ['unauthorized access', 'data leak', 'breach', 'suspicious export'],
    'priority' => 30,
    'is_active' => true
]
```

**Explain:** "This rule says: if a log message contains any of these keywords (unauthorized access, data leak, breach, suspicious export), mark it as critical severity."

---

## Step 9: Show the Notification Path (Code Proof)

Point to these files in order:

1. **app/Services/LogCreationService.php** (line 76):
   ```php
   if (in_array($log->severity, ['critical', 'emergency'])) {
       $this->notifyCritical($log);
   }
   ```
   **Explain:** "When severity is critical, notify admins."

2. **app/Listeners/SendCriticalNotification.php** (line 19):
   ```php
   $this->notificationService->dispatchNotifications($event->log, ['whatsapp', 'sms', 'email', 'pusher']);
   ```
   **Explain:** "This sends the alert through email, SMS, WhatsApp, and Pusher (real-time broadcast)."

3. **app/Notifications/CriticalLogAlert.php** (line 18):
   ```php
   public function via(object $notifiable): array
   {
       return ['mail', 'database', TwilioSmsChannel::class];
   }
   ```
   **Explain:** "This defines which channels to use: email, database, and SMS."

---

## Step 10: Exit Tinker

Type `exit` and press Enter to leave the tinker shell.

---

## Summary for Your Teacher

**"Here's how critical alerts work in GovLog Sentinel:**

1. **Rules are defined** in the database (seeded from ClassificationRulesSeeder.php)
2. **When a log is created**, the classifier checks if the message matches any critical keywords
3. **If it matches**, the severity is set to "critical" automatically
4. **The service immediately sends notifications** to all admin users via email, SMS, WhatsApp, and real-time push
5. **The log appears in the Critical Alerts page** for admins to view and investigate
6. **All admins are notified** both in the database and through external channels

**Live proof:**
- (Show the Critical Alerts page with the new log)
- (Show the tinker output with `is_notified: true`)
- (Open the code files and point to the classification rules and notification chain)"

---

## Quick Command Reference

| What You Need | Command |
|---|---|
| Seed everything | `php artisan db:seed` |
| Start tinker | `php artisan tinker` |
| Create critical log | Paste the code from Step 3 |
| Check if notified | Paste the code from Step 7 |
| Exit tinker | `exit` |
| Clear cache (if needed) | `php artisan cache:clear` |

---

## Troubleshooting

**Q: I don't see my new log on the Critical Alerts page?**
- Refresh the page (Ctrl+F5 or Cmd+Shift+R)
- Make sure you're logged in as admin
- Go to http://localhost/admin/logs/critical directly

**Q: The log was created but severity is not "critical"?**
- Check the message — it needs to match one of the keywords in the ClassificationRulesSeeder
- Or use `'severity' => 'critical'` directly in the create() call to force it

**Q: How do I know if the notification was actually sent?**
- Check the log record in tinker: `dd($log->notification_results);`
- If Twilio/WhatsApp are not configured, those channels will show "error" — that's OK for demo

**Q: How do I test the full email/SMS notification?**
- Email: Check `storage/logs/laravel.log` for mail output
- SMS: If Twilio is configured, check your phone
- Database: The notification is stored in the `notifications` table (check with Tinker or SQL tool)
