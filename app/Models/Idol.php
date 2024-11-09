<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Idol extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, Searchable, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'name',
        'stage_name',
        'birthdate',
        'nationality',
        'debute_date',
        'position',
        'social_media',
        'bio',
        'group_id',
        'slug',
        'spotify_id'
    ];


    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'debute_date' => 'date',
            'social_media' => 'array',
        ];
    }


    public function toSearchableArray(): array
    {
        return $this->only('name', 'stage_name', 'bio', 'slug', 'nationality', 'position');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }


    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeWhereScout(Builder $query, string $search): Builder
    {
        return $query->whereIn('id', self::search($search)->get()->pluck('id'));
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'idol_id');
    }
}
