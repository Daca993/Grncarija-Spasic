<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Grnčarija Spale'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Instagram profile URL (Pratite nas na Instagramu)
    |--------------------------------------------------------------------------
    */
    'instagram_url' => env('INSTAGRAM_URL', 'https://www.instagram.com/grncarija_spale/'),
    'facebook_url' => env('FACEBOOK_URL', 'https://www.facebook.com/savremenagrncarija.spale.5'),

    /*
    |--------------------------------------------------------------------------
    | Contact details (public)
    |--------------------------------------------------------------------------
    */
    'contact_phone' => env('CONTACT_PHONE', '0669532844'),
    'contact_email' => env('CONTACT_EMAIL', 'misa.spale@gmail.com'),

    /*
    |--------------------------------------------------------------------------
    | Wholesale rules
    |--------------------------------------------------------------------------
    */
    'wholesale_only' => (bool) env('WHOLESALE_ONLY', true),
    'wholesale_min_total' => (int) env('WHOLESALE_MIN_TOTAL', 70000),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'sr'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'sr'),

    'available_locales' => ['en', 'sr', 'mk', 'bg', 'sq'],

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    'default_product_image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=600',
    'default_category_image' => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600',
    'hero_background_image' => env('HERO_BACKGROUND_IMAGE', 'assets/img/hero.jpg'),

    /* Cena po veličini: osnovna cena u bazi = srednja; mala = -200, velika = +200 RSD */
    'size_price_offset' => ['mala' => -200, 'srednja' => 0, 'velika' => 200],

];
