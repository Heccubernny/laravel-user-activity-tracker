<?php

namespace Heccubernny\ActivityTracker;

use Heccubernny\ActivityTracker\Models\Activity;
use Heccubernny\ActivityTracker\Observers\ActivityObserver;
use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/activitytracker.php' => config_path('activitytracker.php'),
        ], 'activitytracker-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'activitytracker-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                // coming soon...
                //package-specific artisan commands
            ]);
        }

        // Attach observer to Activity model
        Activity::observe(ActivityObserver::class);

        // coming soon... add a frontend component to visualize user activities

        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'activitytracker');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/activitytracker.php', 'activitytracker');

        $this->app->singleton('activity-tracker', function ($app) {
            return new ActivityTracker();
        });
    }
}
