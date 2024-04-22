<?php

return [
    'mixpanel' => [
        'enabled' => env('MIXPANEL_ENABLED', false),
        'project_token' => env('MIXPANEL_PROJECT_TOKEN', ''),
    ],
];
