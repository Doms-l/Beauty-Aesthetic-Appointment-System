<?php

/*
|--------------------------------------------------------------------------
| M. Cares chatbot facts
|--------------------------------------------------------------------------
|
| Everything the chatbot is allowed to say about the clinic, besides the
| service prices (those are read live from the `services` table).
|
| Edit the values below and the chatbot updates right away, in every
| language. When a promo ends, just delete its line from 'promos'.
|
*/

return [

    'hours' => 'Every day, Monday to Sunday, 8:00 AM to 8:00 PM.',

    'amenities' => 'Free Wi-Fi and free drinking water for all clients.',

    'phone' => '09155168312',

    'facebook' => 'https://www.facebook.com/macaylaanjeaneath.raejell',

    // Shown when a client asks where the clinic is. The chatbot translates
    // the sentence around it but keeps the place names as written.
    'address' => 'Brgy Bito Abuyog Leyte, Front of BV Closa Central School Back Gate',

    // Current promos. Delete a line when the promo ends.
    'promos' => [
        'Micro Brows Retouch is on promo right now: P999 with FREE lashes (regular price P1,500).',
    ],

];
