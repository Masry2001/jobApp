<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary credentials
    |--------------------------------------------------------------------------
    |
    | These values are used by the Cloudinary Laravel SDK to authenticate
    | your application to Cloudinary. The SDK expects a "cloud" array
    | that contains cloud_name, api_key, and api_secret.
    |
    */

    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_KEY'),
        'api_secret' => env('CLOUDINARY_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL for automatic configuration
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Notification URL (webhooks)
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload preset (optional)
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Secure URLs
    |--------------------------------------------------------------------------
    */
    'url' => [
        'secure' => true,
    ],

];
