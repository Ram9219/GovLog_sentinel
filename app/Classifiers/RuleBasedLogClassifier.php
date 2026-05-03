<?php

namespace App\Classifiers;

class RuleBasedLogClassifier implements LogClassifierInterface
{
    public function classify(string $message): string
    {
        $normalizedMessage = strtolower($message);

        if (str_contains($normalizedMessage, 'critical') || str_contains($normalizedMessage, 'fatal')) {
            return 'critical';
        }

        if (str_contains($normalizedMessage, 'error')) {
            return 'error';
        }

        if (str_contains($normalizedMessage, 'warning') || str_contains($normalizedMessage, 'warn')) {
            return 'warning';
        }

        return 'info';
    }
}
