<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default WhatsApp driver that should be used by
    | the notification channel. You may override it per message using the
    | `driver()` method on the message builder.
    |
    */

    'default' => env('WHATSAPP_DRIVER', 'evolution'),

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    |
    | The ISO-3166 alpha-2 country code used to interpret local phone numbers
    | that do not include an international prefix (e.g. "987654321").
    |
    */

    'country' => env('WHATSAPP_DEFAULT_COUNTRY', 'PE'),

    /*
    |--------------------------------------------------------------------------
    | Validate Recipient
    |--------------------------------------------------------------------------
    |
    | When enabled, every recipient number is validated with libphonenumber
    | before it is handed to the driver. Disable it when the provider must
    | receive unvalidated numbers as-is.
    |
    */

    'validate_recipient' => env('WHATSAPP_VALIDATE_RECIPIENT', true),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Each driver maps to a transport registered by the package. The
    | `evolution` driver is available out of the box. The `waha` and `cloud`
    | drivers are planned: uncomment and complete their blocks once their
    | transports ship.
    |
    */

    'drivers' => [

        'evolution' => [
            'base_url' => env('WHATSAPP_EVOLUTION_URL'),
            'api_key' => env('WHATSAPP_EVOLUTION_KEY'),
            'instance' => env('WHATSAPP_EVOLUTION_INSTANCE'),
        ],

        // 'waha' => [
        //     'base_url' => env('WHATSAPP_WAHA_URL'),
        //     'api_key' => env('WHATSAPP_WAHA_KEY'),
        //     'session' => env('WHATSAPP_WAHA_SESSION', 'default'),
        // ],

        // 'cloud' => [
        //     'phone_number_id' => env('WHATSAPP_CLOUD_PHONE_ID'),
        //     'access_token' => env('WHATSAPP_CLOUD_TOKEN'),
        // ],

    ],

];
