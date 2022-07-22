<?php

namespace App\Http\Controllers\Api;

use App\Actions\CommandHistories\FilterDataTableCommandHistoriesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CommandHistoryController
 * @package App\Http\Controllers\Api
 */
class CommandHistoryController extends Controller
{
//    /**
//     * CommandHistoryController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:view,device')->only('index');
//    }

    /**
     * Return a listing of the command histories.
     *
     * @param Request $request
     * @param FilterDataTableCommandHistoriesAction $filterDataTableCommandHistoriesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableCommandHistoriesAction $filterDataTableCommandHistoriesAction, string $deviceId): JsonResponse
    {
        $commandHistories = $filterDataTableCommandHistoriesAction->execute($deviceId, $request->all());

        return $this->apiOk(['commandHistories' => $commandHistories]);
    }
}
