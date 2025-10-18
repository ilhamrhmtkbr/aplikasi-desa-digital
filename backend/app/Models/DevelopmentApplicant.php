<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevelopmentApplicant extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'development_id',
        'user_id',
        'status'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->whereHas('development', function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function development(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Development::class, 'development_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
