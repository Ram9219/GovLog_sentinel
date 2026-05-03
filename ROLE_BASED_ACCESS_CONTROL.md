# Role-Based Access Control Matrix - GovLog Sentinel

## 📋 ACCESS CONTROL SPECIFICATION

Based on your problem statement requiring **role-based access control** for **e-governance platforms**, the system should NOT be admin-only. Here's the detailed specification:

---

## 👥 ROLE DEFINITIONS

### 1. **ADMIN** (System Administrator)
**Permission Level**: Full Access  
**Use Case**: IT Department / System Administrator  
**Database Role Field**: `'admin'`

| Feature | Access | Notes |
|---------|--------|-------|
| View All Logs | ✅ YES | All logs across all departments |
| View Critical Logs | ✅ YES | Immediate access to security incidents |
| Create Logs | ✅ YES | Manual log entry capability |
| Delete Logs | ✅ YES | Archive or remove logs as needed |
| Export Logs | ✅ YES | All formats (CSV, Excel, PDF) |
| View Reports | ✅ YES | Compliance, audit, compliance-specific |
| Download Reports | ✅ YES | All report types with full data |
| Manage Users | ✅ YES | Create, edit, delete, assign roles |
| Assign Roles | ✅ YES | Change user role dynamically |
| Configure Classification Rules | ✅ YES | Create, edit, delete rules |
| Configure Notifications | ✅ YES | Set channels, quiet hours, recipients |
| Access Audit Trail | ✅ YES | See who changed what, when |
| Manage System Settings | ✅ YES | API keys, integrations, database |
| Reset User Passwords | ✅ YES | Emergency access recovery |
| Access Analytics | ✅ YES | All metrics and trends |

---

### 2. **OPERATOR** (Department Coordinator / NOC Officer)
**Permission Level**: Partial Access  
**Use Case**: Government Department Staff / Network Operations Center  
**Database Role Field**: `'operator'`

| Feature | Access | Notes |
|---------|--------|-------|
| View Logs | ✅ YES | Own department + system logs |
| View Critical Logs | ✅ YES | Filtered to relevance |
| Create Logs | ✅ YES | Report incidents manually |
| Delete Logs | ❌ NO | Archive restricted to admin |
| Export Logs | ✅ YES | Own department data only |
| View Reports | ✅ YES | Department-specific reports |
| Download Reports | ✅ YES | Own department reports |
| Manage Users | ❌ NO | Cannot manage other users |
| Assign Roles | ❌ NO | Cannot change permissions |
| Configure Classification Rules | ✅ YES | Create custom rules (admin approval needed) |
| Configure Notifications | ✅ YES | Personal notification preferences only |
| Access Audit Trail | ✅ YES | Own department activity |
| Manage System Settings | ❌ NO | Read-only access to settings |
| Reset User Passwords | ❌ NO | Use self-service or contact admin |
| Access Analytics | ✅ YES | Department-specific analytics |

---

### 3. **VIEWER** (Read-Only User / Management)
**Permission Level**: View-Only  
**Use Case**: Senior Government Officers / Auditors / Management  
**Database Role Field**: `'viewer'`

| Feature | Access | Notes |
|---------|--------|-------|
| View Logs | ✅ YES | Non-sensitive logs only (filtered) |
| View Critical Logs | ⚠️ PARTIAL | Summary only, no sensitive details |
| Create Logs | ❌ NO | Cannot create entries |
| Delete Logs | ❌ NO | Read-only mode |
| Export Logs | ✅ YES | Redacted data only |
| View Reports | ✅ YES | Read-only compliance reports |
| Download Reports | ✅ YES | PDF/Excel with watermarks |
| Manage Users | ❌ NO | No user management |
| Assign Roles | ❌ NO | No permission changes |
| Configure Classification Rules | ❌ NO | Cannot modify rules |
| Configure Notifications | ❌ NO | No configuration access |
| Access Audit Trail | ❌ NO | Cannot audit other users |
| Manage System Settings | ❌ NO | No access to settings |
| Reset User Passwords | ❌ NO | No admin functions |
| Access Analytics | ✅ YES | Public analytics dashboard |

---

### 4. **SYSTEM** (Automated Service)
**Permission Level**: Limited Functional  
**Use Case**: Automated logging from system services, scheduled tasks  
**Database Role Field**: `'system'` (Optional, can use API tokens)

| Feature | Access | Notes |
|---------|--------|-------|
| View Logs | ❌ NO | Write-only access |
| View Critical Logs | ❌ NO | Cannot query logs |
| Create Logs | ✅ YES | Via API endpoint with token |
| Delete Logs | ❌ NO | No deletion capability |
| Export Logs | ❌ NO | Cannot export |
| View Reports | ❌ NO | No UI access |
| Download Reports | ❌ NO | No report access |
| Manage Users | ❌ NO | Service accounts only |
| Assign Roles | ❌ NO | No role management |
| Configure Classification Rules | ❌ NO | Static rules only |
| Configure Notifications | ❌ NO | Pre-configured |
| Access Audit Trail | ❌ NO | Cannot audit |
| Manage System Settings | ❌ NO | No configuration |
| Reset User Passwords | ❌ NO | N/A for system |
| Access Analytics | ❌ NO | No UI access |

---

## 🔒 SECURITY & FILTERING LOGIC

### Data Filtering by Role

```php
// LogController@index - Apply row-level security

if (auth()->user()->role === 'admin') {
    // See all logs
    $query = ServerLog::all();
}
elseif (auth()->user()->role === 'operator') {
    // See own department + system logs
    $query = ServerLog::whereIn('department', [
        auth()->user()->department,
        'SYSTEM'
    ]);
}
elseif (auth()->user()->role === 'viewer') {
    // See only non-sensitive logs
    $query = ServerLog::where('severity', '!=', 'critical')
        ->where('classification', '!=', 'security');
}
```

### Notification Distribution

```
CRITICAL/EMERGENCY Events:
├─> SMS to Admin + On-duty Operator
├─> WhatsApp to NOC
├─> Email to Department Head
└─> Dashboard alert to all viewers

ERROR Events:
├─> Email to Operator
└─> Dashboard notification

WARNING Events:
├─> Dashboard notification
└─> Email to on-request viewers
```

---

## 📊 DATABASE SCHEMA ADDITION

Add these columns to `users` table:

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'operator', 'viewer', 'system'])->default('viewer');
    $table->string('department')->nullable(); // e.g., 'WATER', 'HEALTH', 'ROADS'
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_login_at')->nullable();
    $table->json('notification_preferences')->nullable();
});
```

---

## 🛡️ AUTHORIZATION IMPLEMENTATION

### Create Policy File

```bash
php artisan make:policy LogPolicy --model=ServerLog
```

```php
// app/Policies/LogPolicy.php

class LogPolicy
{
    public function view(User $user, ServerLog $log)
    {
        if ($user->role === 'admin') return true;
        
        if ($user->role === 'operator') {
            return $log->department === $user->department 
                || $log->department === 'SYSTEM';
        }
        
        if ($user->role === 'viewer') {
            return $log->severity !== 'critical' 
                && $log->classification !== 'security';
        }
        
        return false;
    }

    public function delete(User $user, ServerLog $log)
    {
        return $user->role === 'admin';
    }

    public function export(User $user, ServerLog $log)
    {
        return in_array($user->role, ['admin', 'operator', 'viewer']);
    }
}
```

### Create Middleware

```bash
php artisan make:middleware CheckLogPermissions
```

```php
// app/Http/Middleware/CheckLogPermissions.php

class CheckLogPermissions
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user || !in_array($user->role, ['admin', 'operator', 'viewer'])) {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}
```

---

## 🎯 IMPLEMENTATION CHECKLIST

- [ ] Add `department` and `is_active` columns to users table
- [ ] Create `LogPolicy` with authorization rules
- [ ] Create `ReportPolicy` for report access
- [ ] Create `CheckLogPermissions` middleware
- [ ] Create `CheckRolePermissions` middleware
- [ ] Update all controllers to use `authorize()` method
- [ ] Update views to hide buttons based on role
- [ ] Create test users for each role
- [ ] Write tests for role-based access
- [ ] Update Blade templates with role checks: `@can('view', $log)`
- [ ] Add department filtering in queries
- [ ] Configure notification recipients by role

---

## 📝 AICTE COMPLIANCE

This multi-role system ensures:
- ✅ **Principle of Least Privilege**: Users get only needed access
- ✅ **Separation of Duties**: Admins manage, Operators monitor, Viewers audit
- ✅ **Accountability**: Department-based data isolation
- ✅ **Auditability**: Complete access trail maintained
- ✅ **E-Governance Best Practices**: Suitable for multiple government departments

---

## 🚀 EXAMPLE SEEDER

```php
// database/seeders/RolePermissionSeeder.php

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@govlog.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => null,
            'email_verified_at' => now(),
        ]);

        // Operator - Water Department
        User::factory()->create([
            'name' => 'Operator Water',
            'email' => 'operator.water@govlog.local',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'department' => 'WATER',
            'email_verified_at' => now(),
        ]);

        // Operator - Health Department
        User::factory()->create([
            'name' => 'Operator Health',
            'email' => 'operator.health@govlog.local',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'department' => 'HEALTH',
            'email_verified_at' => now(),
        ]);

        // Viewer - Auditor
        User::factory()->create([
            'name' => 'Auditor',
            'email' => 'auditor@govlog.local',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'department' => null,
            'email_verified_at' => now(),
        ]);

        // System Service Account
        User::factory()->create([
            'name' => 'System Logger',
            'email' => 'system@govlog.local',
            'password' => Hash::make(Str::random(32)),
            'role' => 'system',
            'department' => 'SYSTEM',
            'email_verified_at' => now(),
        ]);
    }
}
```

