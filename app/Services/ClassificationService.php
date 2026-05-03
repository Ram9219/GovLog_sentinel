<?php

namespace App\Services;

use App\Models\LogClassificationRule;
use App\Classifiers\RuleBasedLogClassifier;

class ClassificationService
{
    protected $ruleBasedClassifier;

    public function __construct(RuleBasedLogClassifier $ruleBasedClassifier)
    {
        $this->ruleBasedClassifier = $ruleBasedClassifier;
    }

    /**
     * Classify a log entry
     */
    public function classify(string $action, string $message, array $context = []): array
    {
        // First, try database rules (dynamic)
        $dbRule = $this->matchDatabaseRule($action, $message);
        
        if ($dbRule) {
            return [
                'classification' => $dbRule->classification,
                'severity' => $dbRule->severity
            ];
        }

        // Fallback to static rule-based classifier
        $severity = $this->ruleBasedClassifier->classify($message);
        return [
            'classification' => $this->mapSeverityToClassification($severity),
            'severity' => $severity
        ];
    }

    /**
     * Match against database rules
     */
    protected function matchDatabaseRule(string $action, string $message): ?LogClassificationRule
    {
        $searchText = strtolower($action . ' ' . $message);
        
        $rules = LogClassificationRule::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($rules as $rule) {
            $patterns = is_array($rule->patterns) ? $rule->patterns : json_decode($rule->patterns, true);
            
            foreach ($patterns as $pattern) {
                if (strpos($searchText, strtolower($pattern)) !== false) {
                    return $rule;
                }
            }
        }

        return null;
    }

    /**
     * Map severity level to classification
     */
    protected function mapSeverityToClassification(string $severity): string
    {
        return match($severity) {
            'critical', 'emergency' => 'security',
            'error' => 'error_event',
            'warning' => 'warning_event',
            default => 'general'
        };
    }

    /**
     * Get classification statistics
     */
    public function getStatistics(): array
    {
        return [
            'by_classification' => \App\Models\ServerLog::selectRaw('classification, count(*) as total')
                ->groupBy('classification')
                ->get(),
            'by_severity' => \App\Models\ServerLog::selectRaw('severity, count(*) as total')
                ->groupBy('severity')
                ->get()
        ];
    }
}