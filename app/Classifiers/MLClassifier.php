<?php

namespace App\Classifiers;

class MLClassifier implements LogClassifierInterface
{
    public function classify(string $message): string
    {
        return 'unclassified';
    }
}
