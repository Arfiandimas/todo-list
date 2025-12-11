<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\ReqValidator;

class CreateTodoRequest extends ReqValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'string|max:255',
            'assignee' => 'string|max:255',
            'due_date' => 'date_format:Y-m-d|after_or_equal:today',
            'time_tracked' => 'numeric',
            'status' => 'in:pending,open,in_progress,completed',
            'priority' => 'in:low,medium,high'
        ];
        
        if($this->getMethod() == 'POST'){
            $rules = $this->addRequired($rules, ['assignee', 'time_tracked', 'status']);
        }

        return $rules;
    }
}
