composer require heccubernny/activity-tracker
php artisan vendor:publish --provider="Heccubernny\ActivityTracker\ActivityServiceProvider" --tag="activitytracker-config"
php artisan vendor:publish --provider="Heccubernny\ActivityTracker\ActivityServiceProvider" --tag="activitytracker-migrations"


php artisan migrate

Register middleware in app/Http/Kernel.php:
protected $routeMiddleware = [
// ...
'activity.log' => \Heccubernny\ActivityTracker\Middleware\LogRouteActivity::class,
];
Then apply to routes:
Route::get('/dashboard', function () {
// ...
})->middleware('activity.log:visited-dashboard');
Automatic model logging

Add trait to models you want to track (e.g., Post):
use Heccubernny\ActivityTracker\Traits\LogsActivity;

class Post extends Model
{
use LogsActivity;
}


Manual Logging
use the service or model method:
activity()->log([
'user_id' => auth()->id(),
'name' => 'exported-report',
'description' => 'User exported the monthly sales report',
'properties' => ['report' => 'monthly-sales'],
]);

or 

->middleware([LogRouteActivity::class . ':view post,' . '$id']);


// Or model:
$post->logActivity('published', ['description' => 'Published via admin panel']);

user can set this in their .env. 

    // Retention in days (null to keep forever)
    'retention_days' => env('ACTIVITY_RETENTION_DAYS', null),