<?php

namespace App\Presentation\Products\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'category_id' => $this->categoryId ?? $this->category_id,
            'created_at' => $this->createdAt ?? $this->created_at,
            'updated_at' => $this->updatedAt ?? $this->updated_at,
        ];
    }
}
