<?php

namespace App\Services;

use App\Base\ServiceBase;
use App\Exports\ExportTodo;
use App\Responses\ServiceResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportTodoService extends ServiceBase
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
        try{
            $parts = ['todo'];
            if ($this->request->start) $parts[] = 'from-'.$this->request->start;
            if ($this->request->end) $parts[] = 'to-'.$this->request->end;
            $file_name = implode('_', $parts) . '.xlsx';

            return self::success(Excel::download(new ExportTodo($this->request), $file_name));
        }catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }
}
