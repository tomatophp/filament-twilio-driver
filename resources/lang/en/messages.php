<?php

return [
    'settings' => [
        'twilio' => [
            'title' => 'Twilio Integration',
            'description' => 'Configure your Twilio SMS and WhatsApp settings.',
            'active' => 'Twilio Active',
            'account' => 'Twilio Account',
            'sid' => 'Account SID',
            'token' => 'Auth Token',
            'keep_secret' => 'Leave empty to keep the saved token',
            'from' => 'SMS From Number',
            'from_help' => 'The Twilio number SMS messages are sent from, e.g. +12025550123',
            'whatsapp_from' => 'WhatsApp From Number',
            'whatsapp_from_help' => 'The Twilio WhatsApp sender, e.g. +14155238886',
        ],
    ],
];
