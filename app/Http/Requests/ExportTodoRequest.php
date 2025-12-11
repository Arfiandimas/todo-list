<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\ReqValidator;

class ExportTodoRequest extends ReqValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'nullable|sometimes|string|max:255',
            'assignee' => 'nullable|sometimes|string',
            'start' => 'nullable|sometimes|date_format:Y-m-d',
            'end' => 'nullable|sometimes|date_format:Y-m-d|after_or_equal:start',
            'min' => 'nullable|sometimes|numeric|min:0',
            'max' => 'nullable|sometimes|numeric|gte:min',
            'status' => 'nullable|sometimes|string',
            'priority' => 'nullable|sometimes|string'
        ];

        return $rules;
    }
}
