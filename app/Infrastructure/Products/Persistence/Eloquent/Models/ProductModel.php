<?php

namespace App\Infrastructure\Products\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    use LogsActivity, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function newUniqueId(): string
    {
        return (string) Str::uuid7();
    }

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'category_id' => 'string',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'price', 'stock', 'category_id'])
            ->logOnlyDirty()
            ->useLogName('product')
            ->setDescriptionForEvent(fn(string $eventName) => "Product {$eventName}");
    }
}
