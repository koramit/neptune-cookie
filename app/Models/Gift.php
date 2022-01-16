<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'quantity',
        'remain',
        'gift_event_id'
    ];

    public function giftEvent()
    {
        return $this->belongsTo(GiftEvent::class);
    }
}
