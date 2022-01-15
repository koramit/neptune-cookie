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
        return $this->belongsToMany(User::class)
                    ->withPivot('gift_title', 'label_number')
                    ->as('label')
                    ->withTimestamps();
    }

    public function vips()
    {
        return $this->belongsToMany(Participant::class)->withTimestamps();
    }

    public function syncVIPs(array $ids)
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
        $this->vips()->sync($ids);
    }

    public function getParticipantGroupByUserOrgId($id)
    {
        return $this->participantGroups()
                    ->whereHas('participants', fn ($q) => $q->whereId($id))
                    ->first();
    }

    public function drawForUser($user, $labelNumber)
    {
        // group gift remain
        $group = $this->getParticipantGroupByUserOrgId($user->org_id);
        if (!$group || $group->gift_remain == 0) {
            return $this->no_luck_label; // no luck;
        }

        // check available gifts
        $availableGifts = Gift::whereHas('giftEvent', fn ($q) => $q->whereId($this->id))->where('quantity', '<>', 0)->get();
        if (empty($availableGifts)) {
            return $this->no_luck_label; // no luck;
        }

        // group participants remian
        $drewCount = User::whereHas('giftEvents', fn ($q) => $q->whereId($this->id))
                        ->whereIn('org_id', $group->participants()->select('id')->pluck('id'))
                        ->count();
        $participantRemain = $group->participants()->count() - $drewCount;

        // generate and shuffle labels
        $shuffled = collect(array_merge(array_fill(0, $group->gift_remain, 'gift'), array_fill(0, $participantRemain - $group->gift_remain, 'empty')))->shuffle();
        $drawLabel = $shuffled[$labelNumber % $shuffled->count()];
        if ($drawLabel === 'empty') {
            return $this->no_luck_label; // no luck
        }

        // recheck available gifts and random
        $group = $this->getParticipantGroupByUserOrgId($user->org_id);
        if ($group->gift_remain == 0) {
            return $this->no_luck_label; // no luck;
        }

        $availableGifts = Gift::whereHas('giftEvent', fn ($q) => $q->whereId($this->id))->where('quantity', '<>', 0)->get();
        if (empty($availableGifts)) {
            return $this->no_luck_label; // no luck;
        }

        $giftLabels = [];
        foreach ($availableGifts as $gift) {
            $giftLabels = array_merge($giftLabels, array_fill(0, $gift->remain, $gift->id));
        }
        $shuffled = collect($giftLabels)->shuffle();
        $drawGift = $shuffled[$labelNumber % $shuffled->count()];
        $drawGift = Gift::find($drawGift);
        $drawGift->decrement('remain');
        $group->decrement('gift_remain');

        return $drawGift->title;
    }
}
