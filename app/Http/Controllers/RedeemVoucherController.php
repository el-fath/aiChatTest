<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CheckEligibleRequest;
use App\Http\Requests\ValidatePhotoRequest;
use App\Http\Resources\CustomerResource;
use App\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RedeemVoucherController extends Controller
{
    public function checkEligible(CheckEligibleRequest $request)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::find($request->customer_id);
            $voucher = Voucher::find($request->voucher_id);

            $isEligible = $this->isEligible($customer, $voucher);
            if ($isEligible) {
                // check is voucher still available & dos'nt time out
                if ($voucher->lockedCustomers()->count() >= 1000 || $voucher->redeemedCustomers()->count() >= 1000 || $voucher->expired_at <= now())
                    return $this->DataPresenter([], 'voucher is out', 400, false);

                // check is voucher locked to the customer ? if not than lock this voucher to the customer
                if ($customer->lockedVouchers->where('id', $voucher->id)->count() > 0) {
                    return $this->DataPresenter($customer, 'you have locked this voucher, please validate your photo for redeem it');
                } else {
                    $customer->lockedVouchers()->syncWithoutDetaching([$voucher->id, ['type' => 'locked']]);
                }

                DB::commit();

                return $this->DataPresenter(Customer::find($request->customer_id), 'voucher locked');
            }

            return $this->DataPresenter([], 'customer not eligible', 400, false);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getFile() . ":" . $e->getLine() . " (" . $e->getMessage() . ")"
            ], 500);
        }
    }

    /**
     *  Check eligible customer
     */
    public function isEligible($customer, $voucher, $minTransaction = 3 , $totalTransaction = 1000, $milestone = 1) : bool
    {
        // get data transactions in last month
        $from = Carbon::now()->subMonths($milestone);
        $to = Carbon::now();
        $transaction = $customer->transactions->whereBetween('created_at', [$from, $to]);
        
        // count transaction
        $total_transaction = $transaction->count();

        // sum total transaction
        $total_spent_transaction = $transaction->sum('total_spent');
        
        // was cutomer completed 3 purchase transactions within the last 30 days ?
        if ($total_transaction >= $minTransaction && $total_spent_transaction >= $totalTransaction) {

            // was customer redeemed this voucher before ? if no set aligible true
            $redeemed = $customer->redeemedVouchers->where('id', $voucher->id);
            if ($redeemed->count() == 0) return true;
        }
        
        // set aligible false
        return false;
    }

    /**
     * Validate data
     *
     */
    public function validation(ValidatePhotoRequest $request)
    {
        DB::beginTransaction();

        try {
            $photoValidation = $this->validatePhoto();
            $customer = Customer::find($request->customer_id);

            // set default response
            $res = new CustomerResource(NULL);
            $message = 'customer does not lock to this voucher';
            $success = false;
            $code = 400;

            // check is customer was locked this voucher
            if (isset($customer->new_locked_voucher) && $customer->new_locked_voucher->id == $request->voucher_id) {
                
                // unlock voucher
                $customer->lockedVouchers()->detach($request->voucher_id);

                // set expired time
                $carbon = new Carbon($customer->new_locked_voucher->pivot->created_at);
                $expired_at = $carbon->addMinute(10);

                // check is submission time more than 10 minutes
                if (now() <= $expired_at) {

                    // allocate the locked voucher to the customer and return the voucher code.
                    $customer->RedeemedVouchers()->syncWithoutDetaching([$request->voucher_id, ['type' => 'redeemed']]);
                    
                    $res = new CustomerResource(Customer::find($request->customer_id));
                    $message = 'voucher redeemed';
                    $success = true;
                    $code = 200;
                } else {
                    $message = 'submission time is out';
                }

                // check is photo validation is false
                if (!$photoValidation) $message = 'photo is not recognized';
            }

            DB::commit();

            // check if customer was redeemed this voucher
            if (isset($customer->new_redeemed_voucher) && $customer->new_redeemed_voucher->id == $request->voucher_id) {
                $message = 'customer was redeeemed this voucher';
            }

            return $res->additional([
                'success' => $success,
                'message' => $message
            ])->response()->setStatusCode($code);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getFile() . ":" . $e->getLine() . " (" . $e->getMessage() . ")"
            ], 500);
        }
    }

    public function validatePhoto() : bool
    {
        return true;
    }
    
}
