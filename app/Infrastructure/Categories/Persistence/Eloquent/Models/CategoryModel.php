<?php

namespace App\Infrastructure\Categories\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CategoryModel extends Model
{
    use LogsActivity;

    protected $table = 'categories';

    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('category')
            ->setDescriptionForEvent(fn(string $eventName) => "Category {$eventName}");
    }
}
