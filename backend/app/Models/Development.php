<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'description',
        'person_in_charge',
        'start_date',
        'end_date',
        'amount',
        'status'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->where('name', 'like', '%' . $search . '%');
    }

    public function developmentApplicants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DevelopmentApplicant::class, 'development_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
