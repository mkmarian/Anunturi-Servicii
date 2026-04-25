<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Roluri utilizatori
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'customer'  => 'customer',
        'craftsman' => 'craftsman',
        'moderator' => 'moderator',
        'admin'     => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Statusuri anunturi meseriasi
    |--------------------------------------------------------------------------
    */
    'listing_statuses' => [
        'draft'     => 'draft',
        'pending'   => 'pending',
        'published' => 'published',
        'rejected'  => 'rejected',
        'archived'  => 'archived',
    ],

    /*
    |--------------------------------------------------------------------------
    | Statusuri cereri de servicii
    |--------------------------------------------------------------------------
    */
    'request_statuses' => [
        'draft'     => 'draft',
        'pending'   => 'pending',
        'published' => 'published',
        'matched'   => 'matched',
        'closed'    => 'closed',
        'archived'  => 'archived',
    ],

    /*
    |--------------------------------------------------------------------------
    | Statusuri conversatii (chat)
    |--------------------------------------------------------------------------
    */
    'conversation_statuses' => [
        'active'  => 'active',
        'blocked' => 'blocked',
        'closed'  => 'closed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Statusuri utilizatori
    |--------------------------------------------------------------------------
    */
    'user_statuses' => [
        'active'               => 'active',
        'suspended'            => 'suspended',
        'pending_verification' => 'pending_verification',
        'deleted'              => 'deleted',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipuri de pret pentru anunturi
    |--------------------------------------------------------------------------
    */
    'price_types' => [
        'fixed'          => 'fixed',
        'starting_from'  => 'starting_from',
        'negotiable'     => 'negotiable',
        'hourly'         => 'hourly',
        'free'           => 'free',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipuri de buget pentru cereri
    |--------------------------------------------------------------------------
    */
    'budget_types' => [
        'fixed'            => 'fixed',
        'negotiable'       => 'negotiable',
        'estimate_needed'  => 'estimate_needed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipuri de promovare
    |--------------------------------------------------------------------------
    */
    'promotion_types' => [
        'featured'      => 'featured',
        'homepage'      => 'homepage',
        'category_top'  => 'category_top',
    ],

    /*
    |--------------------------------------------------------------------------
    | Motive de raportare
    |--------------------------------------------------------------------------
    */
    'report_reasons' => [
        'spam'          => 'spam',
        'inappropriate' => 'inappropriate',
        'fraud'         => 'fraud',
        'duplicate'     => 'duplicate',
        'harassment'    => 'harassment',
        'other'         => 'other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Paginare
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'listings_per_page'  => 10,
        'requests_per_page'  => 10,
        'messages_per_page'  => 30,
        'admin_per_page'     => 25,
    ],

    /*
    |--------------------------------------------------------------------------
    | Limite publicare (MVP - gratuit)
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'max_active_listings_free'  => 5,
        'max_active_requests_free'  => 3,
        'max_listing_images'        => 8,
        'max_image_size_kb'         => 4096,
        'listing_expires_days'      => 60,
        'request_expires_days'      => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Throttling (requests per minute)
    |--------------------------------------------------------------------------
    */
    'throttle' => [
        'login'         => 10,
        'register'      => 5,
        'send_message'  => 30,
        'create_listing'=> 10,
        'create_request'=> 10,
        'report'        => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Moneda implicita
    |--------------------------------------------------------------------------
    */
    'default_currency' => 'RON',

    /*
    |--------------------------------------------------------------------------
    | Localizare implicita
    |--------------------------------------------------------------------------
    */
    'locale'   => 'ro',
    'timezone' => 'Europe/Bucharest',

    /*
    |--------------------------------------------------------------------------
    | Formate data
    | date_display  - folosit in view-uri Blade
    | date_input    - folosit in validare formulare (dd-mm-yyyy)
    | datetime_display - afisat cu ora
    |--------------------------------------------------------------------------
    */
    'date_display'    => 'd-m-Y',
    'date_input'      => 'd-m-Y',
    'datetime_display' => 'd-m-Y H:i',

];
