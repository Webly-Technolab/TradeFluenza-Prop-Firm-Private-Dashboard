<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$propfirm = App\Models\Propfirm::where('slug', 'webly')->first();

if ($propfirm) {
    echo "Propfirm Found: {$propfirm->name}\n";
    echo "API Token: {$propfirm->api_token}\n";
} else {
    echo "Propfirm 'webly' NOT FOUND in database!\n";
    echo "\nAvailable propfirms:\n";
    foreach (App\Models\Propfirm::all() as $p) {
        echo "- {$p->slug} (API Token: {$p->api_token})\n";
    }
}
