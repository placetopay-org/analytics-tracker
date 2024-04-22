<?php

return [
    'mixpanel' => [
        'enabled' => env('MIXPANEL_ENABLED', false),
        'token' => env('MIXPANEL_PROJECT_TOKEN', ''),
    ],
];
