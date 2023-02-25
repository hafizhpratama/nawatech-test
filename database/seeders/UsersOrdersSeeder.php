<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 users
        $users = User::factory()->count(5)->create();

        // For each user, create a random number of orders between 1 and 5
        foreach ($users as $user) {
            $numOrders = rand(1, 5);
            Order::factory()->count($numOrders)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
