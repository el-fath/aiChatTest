<?php

use App\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create voucher example

        $data = [
            'name' => 'anniversary',
            'code' => 'anniv-20th',
            'expired_at' => Carbon::now()->addWeek(1)
        ];

        Voucher::FirstOrCreate($data);
    }
}
