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
        for ($i = 0; $i <= 88; $i++) {
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


        // ======================
        $party = new GiftEvent();
        $party->user_id = User::first()->id;
        $party->title = 'sim';
        $party->slug = Str::uuid()->toString();
        $party->no_luck_label = '300 บาท';
        $party->save();

        $group = $party->participantGroups()->create([
            'title' => 'team a',
            'gift_quota' => 4,
            'gift_remain' => 4,
        ]);
        $group->syncParticipants([
            1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010,
            1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020, 1021, 1022
        ]);

        $group = $party->participantGroups()->create([
            'title' => 'team b',
            'gift_quota' => 12,
            'gift_remain' => 12,
        ]);
        $group->syncParticipants([
            1023, 1024, 1025, 1026, 1027, 1028, 1029, 1030,
            1031, 1032, 1033, 1034, 1035, 1036, 1037, 1038, 1039, 1040,
            1041, 1042, 1043, 1044, 1045, 1046, 1047, 1048, 1049, 1050,
            1051, 1052, 1053, 1054, 1055, 1056, 1057, 1058, 1059, 1060,
            1071, 1072, 1073, 1074, 1075, 1076, 1077, 1078, 1079, 1080,
            1081, 1082, 1083, 1084, 1085, 1086, 1087, 1088
        ]);

        $party->gifts()->createMany([
            ['title' => '2000 บาท', 'quantity' => 1, 'remain' => 1],
            ['title' => '1500 บาท', 'quantity' => 2, 'remain' => 2],
            ['title' => '1000 บาท', 'quantity' => 5, 'remain' => 5],
            ['title' => '500 บาท', 'quantity' => 10, 'remain' => 10],
        ]);

        $labels = [];
        for ($id = 1; $id <= 88; $id++) {
            $labels[] = $id;
        }
        $shuffled = collect($labels)->shuffle();
        for ($id = 2; $id <= 89; $id++) {
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
        $party->title = 'sim รวม';
        $party->slug = Str::uuid()->toString();
        $party->no_luck_label = '300 บาท';
        $party->save();

        $group = $party->participantGroups()->create([
            'title' => 'รวม',
            'gift_quota' => 18,
            'gift_remain' => 18,
        ]);
        $group->syncParticipants([
            1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010,
            1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020, 1021, 1022,
            1023, 1024, 1025, 1026, 1027, 1028, 1029, 1030,
            1031, 1032, 1033, 1034, 1035, 1036, 1037, 1038, 1039, 1040,
            1041, 1042, 1043, 1044, 1045, 1046, 1047, 1048, 1049, 1050,
            1051, 1052, 1053, 1054, 1055, 1056, 1057, 1058, 1059, 1060,
            1071, 1072, 1073, 1074, 1075, 1076, 1077, 1078, 1079, 1080,
            1081, 1082, 1083, 1084, 1085, 1086, 1087, 1088
        ]);

        $party->gifts()->createMany([
            ['title' => '2000 บาท', 'quantity' => 1, 'remain' => 1],
            ['title' => '1500 บาท', 'quantity' => 2, 'remain' => 2],
            ['title' => '1000 บาท', 'quantity' => 5, 'remain' => 5],
            ['title' => '500 บาท', 'quantity' => 10, 'remain' => 10],
        ]);

        $labels = [];
        for ($id = 1; $id <= 88; $id++) {
            $labels[] = $id;
        }
        $shuffled = collect($labels)->shuffle();
        for ($id = 2; $id <= 89; $id++) {
            $labelNumber = $shuffled->pop();
            $gift = $party->drawForUser(User::find($id), $labelNumber);
            $party->users()->attach([$id => [
                'gift_title' => $gift,
                'label_number' => $labelNumber
            ]]);
        }
    }
}
