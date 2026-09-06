<?php
// Temporary debug file — DELETE after use
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\DpPpdReport;
use App\Models\PejabatPendidikan;

echo "=== USERS ===\n";
foreach (User::with('pejabatPendidikan')->get() as $u) {
    echo "ID:{$u->id} | Name:{$u->name} | Office:" . ($u->pejabatPendidikan?->nama ?? 'NONE') . " | Role:" . implode(',', $u->roles->pluck('name')->toArray()) . "\n";
}

echo "\n=== REPORTS ===\n";
foreach (DpPpdReport::with('pejabatPendidikan')->get() as $r) {
    echo "ID:{$r->id} | Office:" . ($r->pejabatPendidikan?->nama ?? 'NONE') . " | pejabat_pendidikan_id:{$r->pejabat_pendidikan_id}\n";
}

echo "\n=== PEJABATS ===\n";
foreach (PejabatPendidikan::all() as $p) {
    echo "ID:{$p->id} | Nama:{$p->nama} | Jenis:{$p->jenis} | Induk:" . ($p->induk_id ?? 'NULL') . "\n";
}

@unlink(__FILE__); // Self-destruct