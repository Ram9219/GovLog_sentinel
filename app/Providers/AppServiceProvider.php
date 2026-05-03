<?php

namespace App\Providers;

use App\Classifiers\LogClassifierInterface;
use App\Classifiers\RuleBasedLogClassifier;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogClassifierInterface::class, RuleBasedLogClassifier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
