<?php

namespace App\Http\Controllers;

use App\Models\GiftEvent;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class GiftEventExportExcelController extends Controller
{
    public function __invoke(GiftEvent $giftEvent)
    {
        if (Auth::id() != $giftEvent->user_id) {
            abort(403);
        }

        $filename = $giftEvent->title.'.xlsx';
        $data = $giftEvent->users->transform(fn ($p) => [
            'SAP ID' => $p->org_id,
            'ชื่อ' => $p->full_name,
            'จับรางวัลเมื่อ' => $p->label['created_at']->format('Y-m-d H:i:s'),
            'หมายเลขฉลาก' => $p->label['label_number'],
            'รางวัล' => $p->label['gift_title'],
        ]);

        return FastExcel::data($data)->download($filename);
    }
}
