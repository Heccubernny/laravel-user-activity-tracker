
# Laravel User Activity Tracker

> ⚠️ **Note:** This package is currently in development and not yet production-ready. Some bugs and issues may still exist. Use at your own risk.  



A lightweight Laravel package to track user and model activities for web and API applications.  

This package allows you to automatically track model events (created, updated, deleted) and route visits, as well as manually log custom activities. It supports both **JWT-based API authentication** and **web session authentication**.

---
## Installation

Install via Composer:

```bash
composer require heccubernny/activity-tracker
```

Publish configuration and migrations:

```bash
php artisan vendor:publish --provider="Heccubernny\ActivityTracker\ActivityServiceProvider" --tag="activitytracker-config"

php artisan vendor:publish --provider="Heccubernny\ActivityTracker\ActivityServiceProvider" --tag="activitytracker-migrations"

php artisan migrate
```

## Middleware Setup

Register the middleware in ```app/Http/Kernel.php```:

```php
protected $routeMiddleware = [
    // ...
    'activity.log' => \Heccubernny\ActivityTracker\Middleware\LogRouteActivity::class,
];
```

Apply middleware to routes:

```php
Route::get('/dashboard', function () {
    // ...
})->middleware('activity.log:visited-dashboard');
```

You can optionally pass a subject (model) to associate with the activity:

```php
Route::get('/posts/{post}', function (App\Models\Post $post) {
    // ...
})->middleware(\Heccubernny\ActivityTracker\Middleware\LogRouteActivity::class.':view-post,' . $post->id);
```

## Automatic Model Logging

Add the ```LogsActivity``` trait to any model you want to track:

```php
use Heccubernny\ActivityTracker\Traits\LogsActivity;

class Post extends Model
{
    use LogsActivity;
}
```
By default, it tracks:

- created

- updated

- deleted

and logs:

- subject_id

- subject_type

- user_id

- user_type

- description

- properties (full model attributes)

- ip_address, url, method, agent

## Manual Logging

Use the ```activity()``` facade for custom events:

```php
activity()->log([
    'name' => 'exported-report',
    'description' => 'User exported the monthly sales report',
    'properties' => ['report' => 'monthly-sales'],
    // Optional: user_id, user_type, subject_id, subject_type
]);
```

or directly on a model

```php
$post->logActivity('published', ['description' => 'Published via admin panel']);
```

## Configuration (Optional)

You can set the activity retention in your ```.env```:

```env
ACTIVITY_RETENTION_DAYS=null
```

- ```null``` keeps logs forever.

- Set to a number of days to automatically purge old logs.

## Example Usage

Manual logging:

```php
activity()->log([
    'name' => 'exported-report',
    'description' => 'User exported the monthly sales report',
    'properties' => ['report' => 'monthly-sales'],
]);
```

Automatic model logging:

```php
$post = Post::find(1);
$post->logActivity('published', ['description' => 'Published via admin panel']);
```

Route-based logging:

```php
Route::get('/dashboard', function () {
    // ...
})->middleware('activity.log:visited-dashboard');
```

### Credits

Special thanks to [Nuno Maduro](https://github.com/nunomaduro) for his [dry repository](https://github.com/nunomaduro/dry) it was the skeleton inspiration.

License: MIT
