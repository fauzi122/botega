<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::query()->create([
            'id_no' => 'ADM.01',
           'first_name' => 'Ali',
           'last_name' => 'Mustopa',
           'user_type' => 'admin',
           'gender' => 'L',
           'birth_at' => '2003-02-12',
           'email' => 'alimustopaaop@gmail.com',
           'pass' => Hash::make('aliali'),
        ]);

        UserModel::query()->create([
            'id_no' => 'ADM.00',
            'first_name' => 'Admin',
            'last_name' => 'Administrator',
            'user_type' => 'admin',
            'gender' => 'L',
            'birth_at' => '2003-10-12',
            'email' => 'admin@gmail.com',
            'pass' => Hash::make('admin'),
        ]);

        UserModel::query()->create([
            'id_no' => 'MEM.01',
            'first_name' => 'Muhammad',
            'last_name' => 'Iqbal',
            'user_type' => 'member',
            'gender' => 'L',
            'birth_at' => '2004-10-12',
            'email' => 'm.iqbal@gmail.com',
            'pass' => Hash::make('admin'),
        ]);

    }
}
