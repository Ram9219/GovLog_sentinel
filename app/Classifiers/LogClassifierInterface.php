<?php

namespace App\Classifiers;

interface LogClassifierInterface
{
    public function classify(string $message): string;
}
