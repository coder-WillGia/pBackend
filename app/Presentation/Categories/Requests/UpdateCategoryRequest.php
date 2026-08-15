<?php

namespace App\Presentation\Categories\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string' => 'El nombre de la categoría debe ser un texto.',
            'name.max' => 'El nombre de la categoría no puede superar los 100 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
        ];
    }
}
