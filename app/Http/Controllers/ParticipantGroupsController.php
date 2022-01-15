<?php

namespace App\Http\Controllers;

use App\Models\GiftEvent;
use App\Models\ParticipantGroup;
use Illuminate\Support\Facades\Auth;

class ParticipantGroupsController extends Controller
{
    public function store(GiftEvent $giftEvent)
    {
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        $group = $giftEvent->participantGroups()->create([
            'title' => 'กลุ่มใหม่',
            'gift_quota' => 1,
            'gift_remain' => 1,
        ]);

        return $group;
    }

    public function destroy(GiftEvent $giftEvent, ParticipantGroup $participantGroup)
    {
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        $participantGroup->delete();

        return [
            'ok' => true
        ];
    }
}
