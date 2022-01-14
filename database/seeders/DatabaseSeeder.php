<?php

namespace Database\Seeders;

use App\Models\GiftEvent;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 20; $i++) {
            User::create([
                'name' => 'koko'.$i,
                'login' => 'koko'.$i,
                'org_id' => 1000 + $i,
                'full_name' => 'koko momo '.$i,
                'password' => Hash::make('secret'),
            ]);
        }

        $party = new GiftEvent();
        $party->user_id = User::first()->id;
        $party->title = 'new year party!!';
        $party->slug = Str::uuid()->toString();
        $party->no_luck_label = 'ไม่มีตังค์ค่ะ';
        $party->save();

        $group = $party->participantGroups()->create([
                    'title' => 'team a',
                    'gift_quota' => 3,
                    'gift_remain' => 3,
                ]);

        $group->syncParticipants([1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010]);

        $group = $party->participantGroups()->create([
                    'title' => 'team b',
                    'gift_quota' => 3,
                    'gift_remain' => 3,
                ]);

        $group->syncParticipants([1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020]);

        $party->gifts()->createMany([
            ['title' => '300USD', 'quantity' => 1, 'remain' => 1],
            ['title' => '200USD', 'quantity' => 2, 'remain' => 2],
            ['title' => '100USD', 'quantity' => 3, 'remain' => 3],
        ]);

        $labels = [];
        for ($id = 1; $id <= 20; $id++) {
            $labels[] = $id;
        }
        $shuffled = collect($labels)->shuffle();
        for ($id = 2; $id <= 21; $id++) {
            $labelNumber = $shuffled->pop();
            $gift = $party->drawForUser(User::find($id), $labelNumber);
            $party->users()->attach([$id => [
                'gift_title' => $gift,
                'label_number' => $labelNumber
            ]]);
        }


        // ======================
        $party = new GiftEvent();
        $party->user_id = User::first()->id;
        $party->title = 'new year party!!';
        $party->slug = Str::uuid()->toString();
        $party->no_luck_label = 'ไม่มีตังค์ครับ';
        $party->save();

        $group = $party->participantGroups()->create([
                    'title' => 'unity',
                    'gift_quota' => 5,
                    'gift_remain' => 5,
                ]);

        $group->syncParticipants([1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010, 1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020]);

        $party->gifts()->createMany([
            ['title' => '500USD', 'quantity' => 1, 'remain' => 1],
            ['title' => '400USD', 'quantity' => 1, 'remain' => 1],
            ['title' => '300USD', 'quantity' => 1, 'remain' => 1],
            ['title' => '200USD', 'quantity' => 1, 'remain' => 1],
            ['title' => '100USD', 'quantity' => 1, 'remain' => 1],
        ]);

        $labels = [];
        for ($id = 1; $id <= 20; $id++) {
            $labels[] = $id;
        }
        $shuffled = collect($labels)->shuffle();
        for ($id = 2; $id <= 21; $id++) {
            $labelNumber = $shuffled->pop();
            $gift = $party->drawForUser(User::find($id), $labelNumber);
            $party->users()->attach([$id => [
                'gift_title' => $gift,
                'label_number' => $labelNumber
            ]]);
        }
    }
}
