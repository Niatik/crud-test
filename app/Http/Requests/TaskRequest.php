<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', new Enum(TaskStatusEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле «Название» обязательно для заполнения.',
            'title.max' => 'Поле «Название» не должно превышать 255 символов.',
            'status.in' => 'Недопустимое значение статуса. Допустимые: new, in_progress, done.',
        ];
    }
}
