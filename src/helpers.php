<?php

if (!function_exists('activity')) {
    function activity()
    {
        return app('activity-tracker');
    }
}
