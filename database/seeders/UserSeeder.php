<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Souvik Nag',
            'email' => 'souvik@test.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        UserDetail::create([
            'user_id' => $user1->id,
            'slug' => Str::slug($user1->name),
        ]);

        $user2 = User::create([
            'name' => 'Nelson Arafat Ali',
            'email' => 'nelson@test.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        UserDetail::create([
            'user_id' => $user2->id,
            'slug' => Str::slug($user2->name),
        ]);
    }
}
