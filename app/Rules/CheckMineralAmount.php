<?php

namespace App\Rules;

use Closure;
use App\Models\Revenue\Revenue;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckMineralAmount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail)
    {
        if($value > 5){
            $fail('مقدار منرال باید از مقدار باقیمانده منرال قابل انتقال کمتر باشد.');
        }
    }
}
