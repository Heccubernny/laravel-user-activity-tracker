<?php

namespace Heccubernny\ActivityTracker\Traits;

use Heccubernny\ActivityTracker\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', [
                'description' => 'A new ' . class_basename($model) . ' was created',
                'properties'  => $model->getAttributes(),
            ]);
        });

        static::updated(function ($model) {
            $model->logActivity('updated', [
                'description' => class_basename($model) . ' updated',
                'properties'  => $model->getChanges(),
            ]);
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', [
                'description' => class_basename($model) . ' deleted',
            ]);
        });

    }

    public function logActivity(string $name, array $properties = [])
    {
        // $user = Auth::user();


        // $user = Auth::guard(request()->is('api/*') ? 'api' : )->user();


        $user = Auth::guard('api')->user() ?? Auth::guard('web')->user();


        Log::info('Logging activity for user: ' . ($user ? $user->id : 'Guest'));

        // Only log if user is authenticated
        if (!$user) {
            // Optionally log for debugging
            Log::info('Activity not logged: no authenticated user.'.$user);
            return null;
        }

        Log::info('Activity details: ' . json_encode([
             'user_id' => $user,
            'user_type' => $user ? get_class($user) : null,
            'subject_id' => $this->getKey(),
            'subject_type' => get_class($this),
            'name' => $name,
            'description' => $properties['description'] ?? null,
            'properties' => $properties,
            'ip_address' => request()->ip() ?? null,
            'url' => request()->fullUrl() ?? null,
            'method' => request()->method() ?? null,
            'agent' => request()->header('User-Agent'),
        ]));
        return Activity::create([
            'user_id' => $user->id,
            'user_type' => $user ? get_class($user) : null,
            'subject_id' => $this->getKey(),
            'subject_type' => get_class($this),
            'name' => $name,
            'description' => $properties['description'] ?? null,
            'properties' => $properties,
            'ip_address' => request()->ip() ?? null,
            'url' => request()->fullUrl() ?? null,
            'method' => request()->method() ?? null,
            'agent' => request()->header('User-Agent'),
        ]);
    }
}
