<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeadOfFamily extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'identify_number',
        'gender',
        'phone_number',
        'occupation',
        'marital_status'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->orWhere('phone_number', 'like', '%' . $search . '%')
            ->orWhere('identify_number', 'like', '%' . $search . '%');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function familyMember(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FamilyMember::class, 'head_of_family_id', 'id');
    }

    public function socialAssistanceRecipients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialAssistance::class, 'social_assistance_id', 'id');
    }

    public function eventParticipants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventParticipant::class, 'event_id', 'id');
    }
}
