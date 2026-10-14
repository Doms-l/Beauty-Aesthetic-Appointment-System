<?php

/*
|--------------------------------------------------------------------------
| Client levels and booking limits
|--------------------------------------------------------------------------
| A client's level is worked out automatically from how many of their
| appointments the clinic has marked "completed". Change the numbers
| below to change the rules - no other code needs to be touched.
|
|  min_completed    completed appointments needed to reach this level
|  max_active       bookings that can be waiting at the same time
|                   (pending / confirmed / rescheduled, from today on)
|  max_per_day      booking requests that can be sent in one day
|  advance_days     how far ahead a booking date can be
*/

return [

    // A client who cancels this many appointments is banned automatically.
    // Only an admin can unban the account (Admin > Clients / Bans).
    'cancel_ban_limit' => 3,

    // Lowest level first. The last one is the VIP level.
    'levels' => [

        'member' => [
            'name' => 'Member',
            'min_completed' => 0,
            'max_active' => 1,
            'max_per_day' => 2,
            'advance_days' => 14,
            'perks' => 'Online booking and appointment history.',
        ],

        'glow' => [
            'name' => 'Glow Client',
            'min_completed' => 5,
            'max_active' => 3,
            'max_per_day' => 3,
            'advance_days' => 30,
            'perks' => 'More bookings at once and priority booking.',
        ],

        'vip' => [
            'name' => 'VIP',
            'min_completed' => 12,
            'max_active' => 5,
            'max_per_day' => 5,
            'advance_days' => 60,
            'perks' => 'The highest booking limits and first access to promos.',
        ],

    ],

];
