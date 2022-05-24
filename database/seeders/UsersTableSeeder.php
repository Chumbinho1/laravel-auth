<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->admin()->create([
            'email'=>'admin@user.com',
            'phone' => '00000000',
            'cpf' => '14141414141'
       ]);
        User::factory()->count(1)->user()->create([
            'email'=>'user@user.com'
        ]);

        User::factory()->count(3)->user()->create();
    }
}
