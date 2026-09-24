<?php

return [
    'thresholds' => [
        'suspicious' => 30,
        'quarantined' => 60,
        'blocked' => 80,
    ],

    // Country is only a supporting signal and can never block an enquiry alone.
    // Add comma-separated ISO country codes in .env as evidence accumulates.
    'higher_risk_country_codes' => array_values(array_filter(array_map(
        static fn (string $code): string => strtoupper(trim($code)),
        explode(',', env('ENQUIRY_SPAM_HIGHER_RISK_COUNTRIES', 'RU')),
    ))),

    'local_country_code' => env('ENQUIRY_LOCAL_COUNTRY', 'AU'),

    'shortened_link_hosts' => [
        'bit.ly',
        'tinyurl.com',
        't.co',
        'cutt.ly',
        'rebrand.ly',
        'telegra.ph',
        't.me',
    ],

    'disposable_email_domains' => [
        'mailinator.com',
        'guerrillamail.com',
        '10minutemail.com',
        'tempmail.com',
        'yopmail.com',
    ],

    'free_email_domains' => [
        'gmail.com',
        'outlook.com',
        'hotmail.com',
        'yahoo.com',
        'icloud.com',
    ],
];
