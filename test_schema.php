<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

\ = new Illuminate\Foundation\Application(
    realpath(__DIR__)
);

\->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

\ = \->make(Illuminate\Contracts\Http\Kernel::class);

\ = \->handle(
    \ = Illuminate\Http\Request::capture()
);

// Show existing external organizations in the database
\ = App\Models\ExternalOrganization::all();
print_r(\->toArray());

// Show schema for the mails table
\ = DB::getSchemaBuilder()->getColumnListing('mails');
print_r(\);

