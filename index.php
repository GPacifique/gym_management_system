<?php
// cPanel/Apache often points the document root to the project root (public_html).
// Forward requests to Laravel's public/index.php front controller.
// This keeps the repo portable whether the docroot is set to /public or not.

// If you can set your domain's document root to the public/ folder, that's preferred.
// Otherwise, this bootstrap file ensures the app runs correctly.

require __DIR__ . '/public/index.php';
