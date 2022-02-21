<?php

use App\Packages\OAuth2\OAuth2LifetimeHelper;

return [
    'token_lifetime_value' => env('OAUTH2_TOKEN_LIFETIME_VALUE', 1),
    'token_lifetime_type' => env('OAUTH2_TOKEN_LIFETIME_TYPE', OAuth2LifetimeHelper::LIFETIME_TYPE_D),
    'auto_register_routes' => env('OAUTH2_AUTO_REGISTER_ROUTES', true)
];
