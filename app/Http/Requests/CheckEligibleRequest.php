<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckEligibleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|numeric|exists:App\Customer,id',
            'voucher_id' => 'required|numeric|exists:App\Voucher,id',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'customer_id is required',
            'customer_id.numeric' => 'customer_id is integer type',
            'customer_id.exists' => 'customer_id is not existing',
            'voucher_id.required' => 'voucher_id is required',
            'voucher_id.numeric' => 'voucher_id is integer type',
            'voucher_id.exists' => 'voucher_id is not existing',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'messages' => $validator->messages()
        ], 400);

        throw new HttpResponseException($response);
    }
}
