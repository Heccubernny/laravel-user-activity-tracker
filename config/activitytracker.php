<?php
return [
    // Attributes you want to ignore when saving request payloads
    'ignore_attributes' => [
        '_token',
        'password',
        'password_confirmation',
    ],

    // Retention in days (null to keep forever)
    'retention_days' => env('ACTIVITY_RETENTION_DAYS', null),

    // Whether to log anonymous guests
    'log_guests' => true,

    // Maximum payload size for properties (in bytes)
    'max_properties_size' => 65535,
];
