<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageDeletionSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('image_deletion_settings')->insert([
            'type' => 'minutes',
            'value' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
