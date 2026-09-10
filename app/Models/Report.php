<?php

namespace App\Models;

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'protocol',
    'category',
    'description',
    'address',
    'latitude',
    'longitude',
    'plus_code',
    'status',
    'image_path',
])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category'  => ReportCategoryEnum::class,
            'latitude'  => 'decimal:7',
            'longitude' => 'decimal:7',
            'status'    => ReportStatusEnum::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'protocol';
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('status', ReportStatusEnum::Published->value);
    }

    public function imageUrl(): string
    {
        if (str_starts_with($this->image_path, 'attached_assets/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
