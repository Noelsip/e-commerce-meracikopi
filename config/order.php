<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Fee Configuration
    |--------------------------------------------------------------------------
    |
    | This is the service fee that will be charged to customers.
    | This fee can come from payment gateway or third-party services.
    | Set to 0 to disable service fee.
    |
    */
    'service_fee' => (int) env('SERVICE_FEE', 0),
];
