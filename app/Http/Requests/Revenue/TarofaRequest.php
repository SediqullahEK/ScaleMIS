<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TarofaRequest extends FormRequest
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
            'province_id' => 'required',
            'district_id' => 'required|numeric',
            'tarofa_taxpayer_name' => 'nullable|max:150',
            'tarofa_tin_no' => 'nullable|numeric',
            'bank_account_no' => 'required|numeric',
            'mineral_exported_amount' => 'nullable|numeric',
            'tarofa_description' => 'nullable|max:300',
            'total_amount' => 'required|numeric',
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(
    //         response()->json(['success' => false, 'errors' => $validator->errors()], 422)
    //     );
    // }
}
