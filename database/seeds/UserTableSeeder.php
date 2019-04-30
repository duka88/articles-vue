<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'name' => 'admin',
        	'email' => 'admin@mail.com',
        	'role' => 'admin',
        	'password' => Hash::make('password'),
        	'email_verified_at' => Carbon::now()
        ]);
    }
}