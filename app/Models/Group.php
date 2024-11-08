<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Group extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'debut_date',
        'company',
        'bio',
        'social_media',
        'spotify_id'
    ];

    protected function casts(): array
    {
        return [
            'debut_date' => 'date',
            'social_media' => 'array',
        ];
    }


    public function idols(): HasMany
    {
        return $this->hasMany(Idol::class, 'group_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'group_id');
    }
}
