<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Rest;

class RestController extends Controller
{
    public function start(Request $request)
    {
        $work = Work::where([
            'user_id' => $request->userId,
            'date' => $request->date,
        ])->latest()->first();

        if (!$work && $this->checkLastDateWork($request->userId, $request->lastDate)) {
            $newWork = Work::create([
                'user_id' => $request->userId,
                'date' => $request->date,
            ]);
            $item = Rest::create([
                'work_id' => $newWork->id,
                'start' => $request->start,
            ]);
            return response()->json([
                'data' => $item,
                'message' => '休憩開始時刻: '.$item->start,
            ]);
        } elseif (!$work) {
            return response()->json(['message' => '現在未出勤です。']);
        } elseif ($work->end !== '23:59:59.99') {
            return response()->json(['message' => 'すでに退勤済みです。']);
        }

        $item = Rest::create([
            'work_id' => $work->id,
            'start' => $request->start,
        ]);
        return response()->json([
            'data' => $item,
            'message' => '休憩開始時刻: '.$item->start,
        ], 200);
    }

    public function end(Request $request)
    {
        $item = Rest::whereHas('work', function ($query) use ($request) {
            $query->where([
                'user_id' => $request->userId,
                'date' => $request->date,
            ]);
        })->latest()->first();

        if (!$item) {
            return response()->json(['message' => '休憩開始時間が記録されていません。']);
        }

        if ($item->end === '23:59:59.99') {
            $item->update(['end' => $request->end]);
            return response()->json([
                'data' => $item,
                'message' => '休憩終了時刻: '.$item->end,
            ]);
        } else {
            return response()->json(['message' => '休憩開始時間が記録されていません。']);
        }
    }

    private function checkLastDateWork($userId, $lastDate)
    {
        $lastDateWork = Work::where([
            'user_id' => $userId,
            'date' => $lastDate,
        ])->latest()->first();

        return ($lastDateWork?->end === '23:59:59:99') ? true : false;
    }
}
