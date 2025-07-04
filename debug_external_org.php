<?php

require __DIR__.'/vendor/autoload.php';

\ = require_once __DIR__.'/bootstrap/app.php';

\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

// Get an external organization
\ = App\\Models\\ExternalOrganization::first();
echo 'Organization ID: ' . \->id . PHP_EOL;

try {
    // Test the sentMails relationship
    \ = \->sentMails()->get();
    echo 'Sent mails count: ' . \->count() . PHP_EOL;
} catch (Exception \) {
    echo 'Error with sentMails: ' . \->getMessage() . PHP_EOL;
}

try {
    // Test the receivedMails relationship
    \ = \->receivedMails()->get();
    echo 'Received mails count: ' . \->count() . PHP_EOL;
} catch (Exception \) {
    echo 'Error with receivedMails: ' . \->getMessage() . PHP_EOL;
}

echo 'Done!' . PHP_EOL;

