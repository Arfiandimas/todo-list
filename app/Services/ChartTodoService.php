<?php

namespace App\Services;

use App\Base\ServiceBase;
use App\Responses\ServiceResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartTodoService extends ServiceBase
{
    protected $results;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->results = null;
        $this->request = $request;
    }

    /**
     * main method of this service
     *
     * @return ServiceResponse
     */
    public function call(): ServiceResponse
    {
        try {
            $this->results =  match ($this->request->type) {
                "status" => $this->statusSummary(),
                "priority" => $this->prioritySummary(),
                "assignee" => $this->assigneeSummary(),
                default => throw new Exception("type tidak valid")
            };
            
            return self::success($this->results);
        }catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }

    private function statusSummary()
    {
        $data = DB::table('todos')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        return ["status_summary" => [
            "pending"       => $data["pending"] ?? 0,
            "open"          => $data["open"] ?? 0,
            "in_progress"   => $data["in_progress"] ?? 0,
            "completed"     => $data["completed"] ?? 0
        ]];
    }

    private function prioritySummary()
    {
        $data = DB::table('todos')
            ->select('priority', DB::raw('COUNT(*) as total'))
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority');

        return ["priority_summary" => [
            "low"       => $data["low"] ?? 0,
            "medium"    => $data["medium"] ?? 0,
            "high"      => $data["high"] ?? 0
        ]];
    }

    private function assigneeSummary()
    {
        $data = DB::table('todos')
            ->select(
                'assignee',
                DB::raw('COUNT(*) as total_todos'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending_todos"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN time_tracked ELSE 0 END) as total_timetracked_completed_todos")
            )
            ->groupBy('assignee')
            ->get();

        $results = [];
        foreach ($data as $val) {
            $results[$val->assignee] = [
                'total_todos' => (int) $val->total_todos,
                'total_pending_todos' => (int) $val->total_pending_todos,
                'total_timetracked_completed_todos' => (float) $val->total_timetracked_completed_todos,
            ];
        }
        return ["assignee_summary" => $results];
    }
}
