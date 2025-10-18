<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistance extends Model
{
    use SoftDeletes, UUID;
    protected $fillable = [
        'thumbnail',
        'name',
        'category',
        'amount',
        'provider',
        'description',
        'is_available'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->where('name', 'like', '%'.$search.'%')
            ->orWhere('provider', 'like', '%'.$search.'%')
            ->orWhere('amount', 'like', '%'.$search.'%');
    }

    public function socialAssistanceRecipients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialAssistanceRecipient::class, 'social_assistance_id', 'id');
    }
}
