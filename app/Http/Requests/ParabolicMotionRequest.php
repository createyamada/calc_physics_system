<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParabolicMotionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'angle' => ['required' , 'max:90' ,'numeric'],
            'speed' => ['required' , 'max:1000' , 'numeric'],
        ];
    }

    public function attributes()
    {
        return [
            'angle' => '角度',
            'speed'   => '初速度'
        ];
    }
}
