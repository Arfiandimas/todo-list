<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartTodoRequest;
use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\ExportTodoRequest;
use App\Services\ChartTodoService;
use App\Services\CreateTodoService;
use App\Services\ExportTodoService;

class TodoController extends Controller
{
    public function create(CreateTodoRequest $request)
    {
        try {
            $results = (new CreateTodoService($request))->call();
            if ($results->status() != 200) {
                return $this->error($results->message(), __FUNCTION__, $results->status(), null, $results->status());
            }
            return $this->success($results->data(), $results->message(), __FUNCTION__, 201, 201);
        } catch (\Throwable $th) {
            $this->logError($th, __METHOD__);
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    public function export(ExportTodoRequest $request)
    {
        try {
            $results = (new ExportTodoService($request))->call();
            if ($results->status() != 200) {
                return $this->error($results->message(), __FUNCTION__, $results->status(), null, $results->status());
            }
            return $results->data();
        } catch (\Throwable $th) {
            $this->logError($th, __METHOD__);
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    public function chart(ChartTodoRequest $request)
    {
        try {
            $results = (new ChartTodoService($request))->call();
            if ($results->status() != 200) {
                return $this->error($results->message(), __FUNCTION__, $results->status(), null, $results->status());
            }
            return $this->success($results->data(), $results->message(), __FUNCTION__, 200, 200);
        } catch (\Throwable $th) {
            $this->logError($th, __METHOD__);
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }
}
