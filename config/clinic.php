<?php

return [
    // Paste ONLY the src="..." link of the Google Maps <iframe> into .env as CLINIC_MAP_EMBED_URL
    'map_embed_url' => env('CLINIC_MAP_EMBED_URL'),
    // Google Plus Code of the clinic pin (used when no embed link is set)
    'map_query' => env('CLINIC_MAP_QUERY', 'Q225+P8G, Abuyog, Leyte'),
    'name' => 'M. Care Beauty Services',
    'address' => env('CLINIC_ADDRESS', 'Brgy Bito, Abuyog, Leyte (Front of BV Closa Central School, Back Gate)'),
];
