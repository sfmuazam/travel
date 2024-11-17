<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Website extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('websites')->insert([
            'title' => 'Title',
        ]);
    }
}
