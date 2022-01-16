<?php

namespace App\Http\Controllers;

use App\Models\GiftEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class DrawGiftController extends Controller
{
    public function __invoke(GiftEvent $giftEvent)
    {
        $number = Request::input('number');
        if ($giftEvent->users()->wherePivot('label_number', $number)->count()) {
            return back()->withErrors(['number' => 'หมายเลข #'.$number.'  มีผู้จับแล้ว โปรดเลือกใหม่']);
        }

        if (!$giftEvent->canDraw(Auth::user())) {
            abort(403);
        }

        $gift = $giftEvent->drawForUser(Auth::user(), $number);
        $giftEvent->users()->attach([Auth::id() => [
            'gift_title' => $gift,
            'label_number' => $number
        ]]);

        return back()->with('gift', $gift);
    }
}
