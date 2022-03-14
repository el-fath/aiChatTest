<?php

use App\Customer;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // factory(User::class, 3)->create();
        factory(Customer::class, 3)->create();
        $this->call(VoucherSeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
