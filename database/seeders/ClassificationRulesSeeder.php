<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogClassificationRule;

class ClassificationRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name' => 'Authentication Failure',
                'classification' => 'authentication',
                'severity' => 'warning',
                'patterns' => ['failed login', 'invalid password', 'authentication failed', 'invalid credentials'],
                'priority' => 10,
                'is_active' => true
            ],
            [
                'name' => 'Multiple Failed Logins',
                'classification' => 'authentication',
                'severity' => 'error',
                'patterns' => ['multiple failed', 'brute force', 'too many attempts'],
                'priority' => 20,
                'is_active' => true
            ],
            [
                'name' => 'Data Breach Detection',
                'classification' => 'data_breach',
                'severity' => 'critical',
                'patterns' => ['unauthorized access', 'data leak', 'breach', 'suspicious export'],
                'priority' => 30,
                'is_active' => true
            ],
            [
                'name' => 'System Error',
                'classification' => 'system',
                'severity' => 'error',
                'patterns' => ['exception', 'error', 'crash', 'timeout', 'memory exhausted'],
                'priority' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Database Issue',
                'classification' => 'database',
                'severity' => 'error',
                'patterns' => ['deadlock', 'connection failed', 'query timeout', 'database error'],
                'priority' => 20,
                'is_active' => true
            ],
            [
                'name' => 'Student Data Modification',
                'classification' => 'student_management',
                'severity' => 'warning',
                'patterns' => ['student update', 'marks modification', 'grade change', 'admission status'],
                'priority' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Student Deletion',
                'classification' => 'student_management',
                'severity' => 'error',
                'patterns' => ['student delete', 'remove student', 'delete enrollment'],
                'priority' => 25,
                'is_active' => true
            ],
            [
                'name' => 'AICTE Compliance',
                'classification' => 'compliance',
                'severity' => 'info',
                'patterns' => ['aicte', 'compliance', 'audit', 'regulation', 'policy'],
                'priority' => 10,
                'is_active' => true
            ],
            [
                'name' => 'Compliance Violation',
                'classification' => 'compliance',
                'severity' => 'critical',
                'patterns' => ['policy violation', 'compliance failure', 'audit failure', 'regulation breach'],
                'priority' => 30,
                'is_active' => true
            ],
            [
                'name' => 'File Access',
                'classification' => 'file_access',
                'severity' => 'info',
                'patterns' => ['file upload', 'file download', 'document access', 'attachment'],
                'priority' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Sensitive File Access',
                'classification' => 'file_access',
                'severity' => 'warning',
                'patterns' => ['confidential', 'sensitive', 'restricted', 'classified'],
                'priority' => 20,
                'is_active' => true
            ]
        ];

        foreach ($rules as $rule) {
            LogClassificationRule::create($rule);
        }
    }
}