<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by webhook authentication
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'propfirm_slug' => 'required|string|exists:propfirms,slug',
            
            // User/Account Information
            'username' => 'nullable|string|max:255',
            'account_id' => 'nullable|string|max:255',
            
            // Beneficiary Information
            'beneficiary_name' => 'required|string|max:255',
            'beneficiary_email' => 'required|email|max:255',
            
            // Payment Details
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'currency' => 'nullable|string|size:3|in:USD,EUR,GBP,AUD,CAD,JPY,CHF,INR',
            
            // Payment Type
            'payment_type' => 'required|in:upi,bank',
            
            // Conditional validation based on payment type
            'upi_id' => 'required_if:payment_type,upi|nullable|string|max:255',
            
            'beneficiary_account_number' => 'required_if:payment_type,bank|nullable|string|max:255',
            'beneficiary_bank' => 'required_if:payment_type,bank|nullable|string|max:255',
            'account_holder_name' => 'required_if:payment_type,bank|nullable|string|max:255',
            'ifsc_code' => 'required_if:payment_type,bank|nullable|string|size:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            
            'beneficiary_address' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'propfirm_slug.exists' => 'The specified propfirm does not exist or is inactive.',
            'amount.min' => 'The payout amount must be at least 0.01.',
            'amount.max' => 'The payout amount cannot exceed 999,999,999.99.',
            'currency.in' => 'The currency must be one of: USD, EUR, GBP, AUD, CAD, JPY, CHF, INR.',
            'payment_type.required' => 'Payment type is required (upi or bank).',
            'payment_type.in' => 'Payment type must be either upi or bank.',
            'upi_id.required_if' => 'UPI ID is required when payment type is UPI.',
            'beneficiary_account_number.required_if' => 'Account number is required when payment type is bank.',
            'beneficiary_bank.required_if' => 'Bank name is required when payment type is bank.',
            'account_holder_name.required_if' => 'Account holder name is required when payment type is bank.',
            'ifsc_code.required_if' => 'IFSC code is required when payment type is bank.',
            'ifsc_code.regex' => 'IFSC code must be in valid format (e.g., SBIN0001234).',
            'ifsc_code.size' => 'IFSC code must be exactly 11 characters.',
        ];
    }

}
