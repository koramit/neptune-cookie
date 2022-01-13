<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantGroup extends Model
{
    use HasFactory;


    public function giftEvent()
    {
        return $this->belongsTo(GiftEvent::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class)->withTimestamps();
    }

    public function syncParticipants(array $ids)
    {
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        $newParticipant = collect($ids)
                            ->diff(
                                Participant::whereIn('id', $ids)
                                            ->get()
                                            ->pluck(['id'])
                            )->map(fn ($p) => ['id' => $p] + $timestamps)
                            ->toArray();
        Participant::insert($newParticipant);
        $this->participants()->sync($ids);
    }
}
