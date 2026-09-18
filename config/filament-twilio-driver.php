<?php

return [
    /**
     * ---------------------------------------
     * Allow Twilio To Send Notifications
     * ---------------------------------------
     */
    'active' => env('TWILIO_DRIVER_ACTIVE', false),

    /**
     * ---------------------------------------
     * Twilio Account SID
     * ---------------------------------------
     */
    'sid' => env('TWILIO_DRIVER_SID'),

    /**
     * ---------------------------------------
     * Twilio Auth Token
     * ---------------------------------------
     */
    'token' => env('TWILIO_DRIVER_TOKEN'),

    /**
     * ---------------------------------------
     * The Twilio Number SMS Messages Are Sent From
     * ---------------------------------------
     */
    'from' => env('TWILIO_DRIVER_FROM'),

    /**
     * ---------------------------------------
     * The Twilio Number WhatsApp Messages Are Sent From
     * ---------------------------------------
     */
    'whatsapp-from' => env('TWILIO_DRIVER_WHATSAPP_FROM'),

    /**
     * ---------------------------------------
     * The Notifiable Column Holding The Phone Number
     * ---------------------------------------
     */
    'phone-column' => env('TWILIO_DRIVER_PHONE_COLUMN', 'phone'),
];
