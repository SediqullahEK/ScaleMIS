<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RevenueDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'letter_id' => ['required'],
            'date' => ['required','date'],
            'tazkira_no' => ['max:15'],
            'license_no' => ['max:20'],
            'tin_no' => ['numeric'],
            'mineral_area' => ['required'],
            'mineral_amount' => ['required','numeric'],
            'unit_price' => ['required','numeric'],
            'mineral_total_price' => ['required','numeric'],
            'district_id' => ['required'],
            'unit_id' => ['required'],
            'mineral_id' => ['required'],
            'weighing_price' => ['required'],
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json(
    //         ['success' => false,'errors' => $validator->errors()],422
    //     ));
    // }
}
