<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class InvoiceSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        Invoice::create([
            'transaction_id' => 1,
            'expires_at' => Carbon::now()->addHours(5),
            'status' => 'pending'
        ]);

        Invoice::create([
            'transaction_id' => 2,
            'expires_at' => Carbon::now()->subHours(1),
            'status' => 'pending'
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
