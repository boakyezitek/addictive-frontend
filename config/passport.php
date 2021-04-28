<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OAUTH_CLIENT_ID
    |--------------------------------------------------------------------------
    |
    | This value is the client id used by the application for the
    | password grant.
    |
    */
    'client_id' => env('OAUTH_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | OAUTH_CLIENT_SECRET
    |--------------------------------------------------------------------------
    |
    | This value is the secret used by the application for the
    | password grant.
    |
    */
    'client_secret' => env('OAUTH_CLIENT_SECRET'),

    'client_uuids' => false,
];
