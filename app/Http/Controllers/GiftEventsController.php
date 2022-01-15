<?php

namespace App\Http\Controllers;

use App\Models\GiftEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GiftEventsController extends Controller
{
    public function index()
    {
        return Inertia::render('GiftEvent/Index', [
            'myGiftEvents' => Auth::user()->myGiftEvents()->select(['title', 'slug'])->get()
        ]);
    }

    public function store()
    {
        $event = new GiftEvent();
        $event->user_id = Auth::id();
        $event->title = Request::input('title');
        $event->no_luck_label = Request::input('no_luck_label');
        $event->slug = Str::uuid()->toString();
        $event->save();

        return Redirect::route('gift_events.edit', $event);
    }

    public function edit(GiftEvent $giftEvent)
    {
        $giftEvent->load('participantGroups', 'gifts');

        return Inertia::render('GiftEvent/Edit', [
            'giftEvent' => [
                'link' => route('gift_events.show', $giftEvent),
                'slug' => $giftEvent->slug,
                'title' => $giftEvent->title,
                'no_luck_label' => $giftEvent->no_luck_label,
                'groups' => $giftEvent->participantGroups()->with('participants')->get()->transform(fn ($g) => [
                    'id' => $g->id,
                    'title' => $g->title,
                    'gift_quota' => $g->gift_quota,
                    'participants' => $g->participants->map(fn ($p) => $p->id)->join(' '),
                ]),
                'gifts' => $giftEvent->gifts->transform(fn ($g) => [
                    'id' => $g->id,
                    'title' => $g->title,
                    'quantity' => $g->quantity,
                ]),
                'vips' => $giftEvent->vips->map(fn ($p) => $p->id)->join(' ')
            ]
        ]);
    }
}
