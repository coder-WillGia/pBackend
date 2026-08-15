<?php

namespace App\Presentation\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|uuid|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser un texto.',
            'name.max' => 'El nombre del producto no puede superar los 255 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.gt' => 'El precio debe ser mayor que 0.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock mínimo debe ser 0.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.uuid' => 'La categoría debe ser un UUID válido.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
