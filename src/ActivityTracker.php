<?php

namespace Heccubernny\ActivityTracker;

use Heccubernny\ActivityTracker\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityTracker
{
    public function log(array $data): ?Activity
    {
        // Determine authenticated user (API JWT or web session)
        $user = Auth::guard('api')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            logger()->info('Activity not logged: no authenticated user.');
            return null;
        }

        logger()->info('Logging activity for subject: ' . $data['subject']);
        // Determine subject if a model is passed
        $subjectId = $data['subject']?->getKey() ?? null;
        $subjectType = $data['subject'] ? get_class($data['subject']) : null;

        $activityData = [
            'user_id'      => $user->id,
            'user_type'    => get_class($user),
            'subject_id'   => $subjectId,
            'subject_type' => $subjectType,
            'name'         => $data['name'] ?? 'unnamed',
            'description'  => $data['description'] ?? null,
            'properties'   => $data['properties'] ?? [],
            'ip_address'   => request()->ip() ?? null,
            'url'          => request()->fullUrl() ?? null,
            'method'       => request()->method() ?? null,
            'agent'        => substr(request()->header('User-Agent') ?? '', 0, 1024),
        ];

        return Activity::create($activityData);
    }
}
