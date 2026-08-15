<?php

namespace App\Infrastructure\Categories\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class CategoryModel extends Model
{
    use LogsActivity, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function newUniqueId(): string
    {
        return (string) Str::uuid7();
    }

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
