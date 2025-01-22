<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | The following parameters control the application's rate limiting settings.
    | The "max_attempts" option specifies the maximum number of requests that
    | can be made within the time window defined by "decay_minutes". Once the
    | limit is reached, further requests will be denied until the window resets.
    |
    */

    'max_attempts' => env('MAX_ATTEMPTS', 5),
    'decay_minutes' => env('DECAY_MINUTES', 1),
];
