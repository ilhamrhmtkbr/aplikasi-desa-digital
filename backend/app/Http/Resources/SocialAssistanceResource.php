<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAssistanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'thumbnail' => asset('storage/'.$this->thumbnail),
            'name' => $this->name,
            'category' => $this->category,
            'amount' => $this->amount,
            'provider' => $this->provider,
            'description' => $this->description,
            'social_assistance_recipients' => SocialAssistanceRecipientsResource::collection($this->whenLoaded('socialAssistanceRecipients')),
            'is_available' => $this->is_available,
            'created_at' => $this->created_at
        ];
    }
}
