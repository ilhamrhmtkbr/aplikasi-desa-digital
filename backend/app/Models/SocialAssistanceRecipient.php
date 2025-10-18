<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistanceRecipient extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'social_assistance_id',
        'head_of_family_id',
        'amount',
        'reason',
        'bank',
        'account_number',
        'proof',
        'status'
    ];

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->whereHas('headOfFamily', function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
               $query->where('name', 'like', '%'. $search .'%');
            });
        })->orWhere('reason', 'like', "%$search%");
    }

    public function socialAssistance(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SocialAssistance::class, 'social_assistance_id', 'id');
    }

    public function headOfFamily(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(HeadOfFamily::class, 'head_of_family_id', 'id')->withTrashed();
    }
}
