<?php

namespace Heccubernny\ActivityTracker\Middleware;

use Closure;
use Heccubernny\ActivityTracker\Models\Activity;
use Illuminate\Support\Facades\Auth;

class LogRouteActivity
{
    public function handle($request, Closure $next, $name = null, $subject = null)
    {
        $response = $next($request);

        try {

            $user = Auth::guard('api')->user() ?? Auth::guard('web')->user();



            $subjectId = null;
            $subjectType = null;
            $subjectAttributes = [];

            if ($subject && is_object($subject)) {
                $subjectId = $subject->getKey();
                $subjectType = get_class($subject);
                $subjectAttributes = $subject->getAttributes();
            }


            Activity::create([
                'user_id' => $user?->id, //$user?->getKey()
                'user_type' => $user ? get_class($user) : null,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
                'name' => $name ?? $request->route()?->getName() ?? $request->method().' '.$request->path(),
                'description' => $subject
                    ? 'Activity on ' . class_basename($subject)
                    : 'Visited route: ' . $request->path(),
                'properties' => [
                    'status' => $response->getStatusCode(),
                    'request' => $request->except(array_keys(config('activitytracker.ignore_attributes', []))),
                    'subject_attributes' => $subjectAttributes
                ],
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'agent' => $request->header('User-Agent'),
            ]);
        } catch (\Throwable $e) {
            logger()->error('ActivityTracker logging failed: '.$e->getMessage());
        }

        return $response;
    }
}
