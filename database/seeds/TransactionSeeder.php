<?php

use App\PurchaseTransaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create transaction example for eligible testing

        PurchaseTransaction::Insert([
            // eligible user with id 1 = true
            [
                'customer_id' => 1,
                'total_spent' => 200,
                'total_saving' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 1,
                'total_spent' => 400,
                'total_saving' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 1,
                'total_spent' => 400,
                'total_saving' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // eligible user with id 2 = false
            [
                'customer_id' => 2,
                'total_spent' => 1000,
                'total_saving' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 2,
                'total_spent' => 1000,
                'total_saving' => 100,
                'created_at' => Carbon::now()->subMonth(1)->subWeek(1),
                'updated_at' => Carbon::now()->subMonth(1)->subWeek(1)
            ],
            [
                'customer_id' => 2,
                'total_spent' => 1000,
                'total_saving' => 100,
                'created_at' => Carbon::now()->subMonth(1)->subWeek(2),
                'updated_at' => Carbon::now()->subMonth(1)->subWeek(2)
            ],
        
            // eligible user with id 3 = false
            [
                'customer_id' => 3,
                'total_spent' => 200,
                'total_saving' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 3,
                'total_spent' => 100,
                'total_saving' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 3,
                'total_spent' => 100,
                'total_saving' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 3,
                'total_spent' => 100,
                'total_saving' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
