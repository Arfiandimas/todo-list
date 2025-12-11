<?php

namespace App\Http\Requests\Base;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReqValidator extends FormRequest
{
    use ApiResponser;
    /**
     * Overide ValidationException
     * Parameters did not pass validation
     *
     * @param ValidationException $exception
     * @return \Illuminate\Http\Response 400
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->errors()->first(), null, 422, null, 422));
    }
    
    protected function addRequired($validateRules, $except = []) {
        foreach ($validateRules as $key => $n) {
            if (in_array($key, $except) && !empty($except)) {
                continue;
            }

            if (is_array($n)) {
                $validateRules[$key][] = 'required';
            } else {
                $validateRules[$key] = $n . '|required';
            }
        }
        return $validateRules;
    }
}
