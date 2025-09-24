<?php


return [
    // Default standard shift length in hours. Can be overridden via query (?shift_hours=7.5)
    'standard_shift_hours' => 8,

    // Emails with access to Settings page
    'admin_emails' => [
        'jouni.ikaheimo@uttu.fi',
    ],

    // In development, allow admin features without configuring emails.
    // Avoid calling env() here to prevent container resolution issues.
    'dev_bypass_admin' => in_array($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'production', ['local', 'development', 'testing'], true),
];
