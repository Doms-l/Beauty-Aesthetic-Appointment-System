<?php

return [

    /*
    | Where "new booking" emails go to the owner.
    | One address, or several separated by commas. When empty, every
    | admin account's email is used instead.
    */
    'owner_email' => env('OWNER_NOTIFY_EMAIL'),

    /*
    | The little note at the bottom of the "booking confirmed" email.
    | Change the text here, or set CONFIRMATION_NOTE in .env. Leave it
    | empty ("") to send the email without a note.
    */
    'confirmation_note' => env(
        'CONFIRMATION_NOTE',
        'Thank you for choosing M. Care! Please arrive about 10 minutes early so we can start on time. '
        . 'If you need to change your appointment, let us know as soon as you can. We can\'t wait to pamper you!'
    ),

];
