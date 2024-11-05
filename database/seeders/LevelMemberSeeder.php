<?php

namespace Database\Seeders;

use App\Models\LevelMemberModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LevelMemberModel::query()->insert([
            ['level_name'=>'Silver', 'description' => '', 'level'=>2, 'kategori'=>'UMUM', 'limit_transaction'=>99999999],
            ['level_name'=>'Gold', 'description' => '','level'=>3, 'kategori'=>'UMUM', 'limit_transaction'=>499999999],
            ['level_name'=>'Diamond', 'description' => '','level'=>4, 'kategori'=>'UMUM', 'limit_transaction'=>999999999],
            ['level_name'=>'Platinum', 'description' => '','level'=>5, 'kategori'=>'UMUM', 'limit_transaction'=>-1],
            ['level_name'=>'Profesional', 'description' => '','level'=>6, 'kategori'=>'MEMBER PRO', 'limit_transaction'=>0],
        ]);

    }
}
