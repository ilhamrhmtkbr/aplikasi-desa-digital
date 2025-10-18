<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'description',
        'price',
        'date',
        'time',
        'is_active'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->where('name', 'like', '%' . $search . '%');
    }

    public function eventParticipant()
    {
        return $this->hasMany(EventParticipant::class, 'event_id', 'id');
    }
}
