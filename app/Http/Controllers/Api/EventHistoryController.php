<?php

namespace App\Http\Controllers\Api;

use App\Actions\EventHistories\FilterDataTableEventHistoriesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class EventHistoryController
 * @package App\Http\Controllers\Api
 */
class EventHistoryController extends Controller
{
//    /**
//     * EventHistoryController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:view,device')->only('index');
//    }

    /**
     * Return a listing of the event histories.
     *
     * @param Request $request
     * @param FilterDataTableEventHistoriesAction $filterDataTableEventHistoriesAction
     * @param string $deviceId
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableEventHistoriesAction $filterDataTableEventHistoriesAction, string $deviceId): JsonResponse
    {
        $eventHistories = $filterDataTableEventHistoriesAction->execute($deviceId, $request->all());

        return $this->apiOk(['eventHistories' => $eventHistories]);
    }
}
