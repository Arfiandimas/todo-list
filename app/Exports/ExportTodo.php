<?php

namespace App\Exports;

use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTodo implements FromArray, WithHeadings
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function array(): array
    {
        $request = $this->request;

        // Build query
        $query = Todo::query()
            ->when($request->filled('title'), function($q) use ($request) {
                $q->where('title', 'ILIKE', '%'.$request->title.'%');
            })
            ->when($request->filled('assignee'), function($q) use ($request) {
                $q->whereIn('assignee', explode(',', $request->assignee));
            })
            ->when($request->filled('start'), function($q) use ($request) {
                $q->where('due_date', '>=', $request->start);
            })
            ->when($request->filled('end'), function($q) use ($request) {
                $q->where('due_date', '<=', $request->end);
            })
            ->when($request->filled('min'), function($q) use ($request) {
                $q->where('time_tracked', '>=', $request->min);
            })
            ->when($request->filled('max'), function($q) use ($request) {
                $q->where('time_tracked', '<=', $request->max);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->whereIn('status', explode(',', $request->status));
            })
            ->when($request->filled('priority'), function($q) use ($request) {
                $q->whereIn('priority', explode(',', $request->priority));
            });

        // Get data in the correct order
        $rows = $query->get([
            'title',
            'assignee',
            'due_date',
            'time_tracked',
            'status',
            'priority'
        ])->toArray();

        // Build summary row
        $totalTodos = count($rows);
        $totalTimeTracked = collect($rows)->sum('time_tracked');

        $summary = [
            "TOTAL $totalTodos",
            '',
            '',
            $totalTimeTracked,
            '',
            ''
        ];

        // Append summary row
        $rows[] = $summary;

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked',
            'Status',
            'Priority'
        ];
    }
}
