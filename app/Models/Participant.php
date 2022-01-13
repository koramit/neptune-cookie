<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    public function participantGroups()
    {
        return $this->belongsToMany(ParticipantGroup::class)->withTimestamps();
    }
}
