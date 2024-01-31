<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::connection()->beginTransaction();
        //ユーザーのダミーデータを2つ作成
        DB::table('users')->insert([
            [
                //admin権限を持つユーザー
                'user_name'      => 'admin',
                'user_id'     => 'admin@test',
                'role'  => 0,
                'password'  => Hash::make('pass'),
                'login_cnt'  => 0,
            ],
            [
                //admin権限を持たないユーザー
                'user_name'     => 'user',
                'user_id'    => 'user@test',
                'role'  => 1,
                'password' => Hash::make('pass'),
                'login_cnt'  => 0,
            ],
        ]);
        DB::connection()->commit();
    }
}
