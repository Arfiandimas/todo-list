<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\ReqValidator;
use Illuminate\Foundation\Http\FormRequest;

class ChartTodoRequest extends ReqValidator
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "type" => "required|in:status,priority,assignee"
        ];
    }
}
