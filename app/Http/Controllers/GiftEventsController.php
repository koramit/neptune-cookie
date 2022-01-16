<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftEvent;
use App\Models\Participant;
use App\Models\ParticipantGroup;
use Carbon\Carbon;
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
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        $giftEvent->load('participantGroups', 'gifts');

        return Inertia::render('GiftEvent/Edit', [
            'giftEvent' => [
                'link' => route('gift_events.show', $giftEvent),
                'slug' => $giftEvent->slug,
                'title' => $giftEvent->title,
                'no_luck_label' => $giftEvent->no_luck_label,
                'datetime_start' => $giftEvent->datetime_start?->format('Y-m-d H:i'),
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

    public function update(GiftEvent $giftEvent)
    {
        if ($giftEvent->user_id != Auth::id()) {
            abort(403);
        }

        Request::validate([
            'title' => 'required|string',
            'no_luck_label' => 'required|string',
            'datetime_start' => 'required|date',
        ]);

        $data = Request::all();

        $giftEvent->title = $data['title'];
        $giftEvent->no_luck_label = $data['no_luck_label'];
        $giftEvent->datetime_start = $data['datetime_start'];
        $giftEvent->save();

        // VIPs
        if ($data['vips']) {
            $giftEvent->syncVIPs(explode(' ', $data['vips']));
        }

        // groups
        foreach ($data['groups'] as $groupData) {
            $group = ParticipantGroup::find($groupData['id']);
            if (!$group) {
                continue;
            }

            $group->title = $groupData['title'];
            $group->gift_quota = $groupData['gift_quota'];
            $group->gift_remain = $groupData['gift_quota'];
            $group->save();

            if ($groupData['participants']) {
                $group->syncParticipants(explode(' ', $groupData['participants']));
            }
        }

        // gifts
        foreach ($data['gifts'] as $giftData) {
            $gift = Gift::find($giftData['id']);
            if (!$gift) {
                continue;
            }

            $gift->title = $giftData['title'];
            $gift->quantity = $giftData['quantity'];
            $gift->remain = $giftData['quantity'];
            $gift->save();
        }


        return Redirect::route('home');
    }

    public function show(GiftEvent $giftEvent)
    {
        // check invitation if not redirect to notice page
        // if (!$giftEvent->canAccess(Auth::user())) {
        //     abort(404);
        // }

        // check invitation if not redirect to wait page
        // if (now()->lessThan($giftEvent->datetime_start)) {
        //     abort(404);
        // }

        return Inertia::render('GiftEvent/Show', [
            'giftEvent' => [
                'slug' => $giftEvent->slug,
                'title' => $giftEvent->title,
                'no_luck_label' => $giftEvent->no_luck_label,
                'participantCount' => Participant::whereHas('participantGroups', fn ($q) => $q->whereIn('id', $giftEvent->participantGroups()->pluck('id')))->count(),
                'drewLabels' => $giftEvent->users->transform(fn ($u) => [
                    'name' => $u->login,
                    'gift' => $u->label['gift_title'],
                    'number' => $u->label['label_number'],
                ]),
                'organizer' => $giftEvent->user_id == Auth::id(),
                'can' => [
                    'draw' => $giftEvent->canDraw(Auth::user()),
                    'start' => $giftEvent->datetime_start->lessThan(now()),
                    'access' => $giftEvent->canAccess(Auth::user()),
                ]
            ]
        ]);
    }
}
