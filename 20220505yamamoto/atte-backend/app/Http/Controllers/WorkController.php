<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;

class WorkController extends Controller
{
    public function index($date)
    {
        $items = Work::where(['date' => $date])
            ->with('user')
            ->with('rests')
            ->paginate(5);

        return ($items) ?
            response()->json(['data' => $items], 200) :
            response()->json(['message' => '勤務履歴はありません。']);
    }

    public function start(Request $request)
    {
        $work = Work::where([
            'user_id' => $request->userId,
            'date' => $request->date,
        ])->latest()->first();

        if (!$work || $work->start === '00:00:00.00' && $work->end !== '23:59:59.99') {
            $item = Work::create([
                'user_id' => $request->userId,
                'date' => $request->date,
                'start' => $request->start,
            ]);
            return response()->json([
                'data' => $item,
                'message' => '出勤時刻: '.$item->start,
            ]);
        } elseif ($work->start === '00:00:00.00' && $work->end === '23:59:59.99') {
            return response()->json(['message' => '退勤処理をおこなってから再試行してください。']);
        } else {
            return response()->json(['message' => '出勤可能回数は1日1回までです。']);
        }
    }

    public function end(Request $request)
    {
        $work = Work::where([
            'user_id' => $request->userId,
            'date' => $request->date,
        ])->with('rests')->latest()->first();

        if (!$work) {
            $item = Work::create([
                'user_id' => $request->userId,
                'date' => $request->date,
                'end' => $request->end,
            ]);
            return response()->json([
                'data' => $item,
                'message' => '退勤時刻: '.$item->end,
        ]);
        } elseif ($work->end === '23:59:59.99') {
            $work->update(['end' => $request->end]);
            return response()->json([
                'data' => $work,
                'message' => '退勤時刻: '.$work->end,
            ]);

        } elseif ($work->start === '00:00:00.00') {
            return response()->json(['message' => '現在未出勤です。']);
        } else {
            return response()->json(['message' => '退勤可能回数は1日1回までです。']);
        }
    }
}
