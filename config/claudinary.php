<?php

return [
    // Paket najčešće čita baš ovaj ključ:
    'cloud_url' => env('CLOUDINARY_URL'),

    // Dodatno (fallback) ako ikad zatreba:
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),

    'secure' => true,
];