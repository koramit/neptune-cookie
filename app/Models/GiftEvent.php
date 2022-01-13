<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftEvent extends Model
{
    use HasFactory;

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function participantGroups()
    {
        return $this->hasMany(ParticipantGroup::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('gift_id', 'number')->as('label')->withTimestamps();
    }
}
