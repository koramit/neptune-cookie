<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftEvent;
use Illuminate\Support\Facades\Auth;

class GiftsController extends Controller
{
    public function store(GiftEvent $giftEvent)
    {
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        $gift = $giftEvent->gifts()->create([
            'title' => 'รางวัล',
            'quantity' => 1,
            'gift_remain' => 1,
        ]);

        return $gift;
    }

    public function destroy(GiftEvent $giftEvent, Gift $gift)
    {
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        $gift->delete();

        return [
            'ok' => true
        ];
    }
}
