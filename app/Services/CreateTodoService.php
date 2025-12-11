<?php

namespace App\Services;

use App\Base\ServiceBase;
use App\Models\Todo;
use App\Responses\ServiceResponse;
use Illuminate\Http\Request;

class CreateTodoService extends ServiceBase
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
            $this->results = Todo::create($this->request->toArray())->fresh();
            return self::success($this->results);
        }catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }
}
