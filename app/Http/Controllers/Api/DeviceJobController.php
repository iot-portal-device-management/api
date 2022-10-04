<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceJob\CalculateDeviceJobProgressStatusAction;
use App\Actions\DeviceJob\CreateDeviceJobAction;
use App\Actions\DeviceJob\FilterDataTableDeviceJobDeviceCommandsAction;
use App\Actions\DeviceJob\FilterDataTableDeviceJobsAction;
use App\Actions\DeviceJob\FindDeviceJobByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceJobRequest;
use App\Http\Requests\ValidateDeviceJobFieldsRequest;
use App\Http\Resources\DeviceCommandCollectionPagination;
use App\Http\Resources\DeviceJobCollectionPagination;
use App\Http\Resources\DeviceJobResource;
use App\Jobs\ProcessDeviceJobJob;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceJobController
 * @package App\Http\Controllers\Api
 */
class DeviceJobController extends Controller
{
//    /**
//     * DeviceJobController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\DeviceJob')->only('index');
//        $this->middleware('can:create,App\Models\DeviceJob')->only('store');
//        $this->middleware('can:deleteMany,App\Models\DeviceJob')->only('destroySelected');;
//    }

    /**
     * Return a listing of the device job.
     *
     * @param Request $request
     * @param FilterDataTableDeviceJobsAction $filterDataTableDeviceJobsAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceJobsAction $filterDataTableDeviceJobsAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $deviceJobs = $filterDataTableDeviceJobsAction->execute($data);

        return $this->apiOk(['deviceJobs' => new DeviceJobCollectionPagination($deviceJobs)]);
    }

    /**
     * Store a newly created device job in storage.
     *
     * @param StoreDeviceJobRequest $request
     * @param CreateDeviceJobAction $createDeviceJobAction
     * @return JsonResponse
     */
    public function store(StoreDeviceJobRequest $request, CreateDeviceJobAction $createDeviceJobAction): JsonResponse
    {
        $data = $request->validated();
        $data['userId'] = Auth::id();

        $deviceJob = $createDeviceJobAction->execute($data);

        if ($deviceJob->exists) {
            ProcessDeviceJobJob::dispatch($deviceJob);
            return $this->apiOk(['deviceJob' => new DeviceJobResource($deviceJob)]);
        }

        return $this->apiInternalServerError('Failed to create device job.');
    }

    /**
     * Return the specified device job.
     *
     * @param FindDeviceJobByIdAction $findDeviceJobByIdAction
     * @param string $deviceJobId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(FindDeviceJobByIdAction $findDeviceJobByIdAction, string $deviceJobId): JsonResponse
    {
        $deviceJob = $findDeviceJobByIdAction->execute($deviceJobId);

        $this->authorize('view', $deviceJob);

        return $this->apiOk(['deviceJob' => new DeviceJobResource($deviceJob)]);
    }

    /**
     * Return the status of the device job.
     *
     * @param CalculateDeviceJobProgressStatusAction $calculateDeviceJobProgressStatusAction
     * @param string $deviceJobId
     * @return JsonResponse
     */
    public function showProgressStatus(
        CalculateDeviceJobProgressStatusAction $calculateDeviceJobProgressStatusAction,
        string $deviceJobId
    ): JsonResponse
    {
        $deviceJobProgressStatus = $calculateDeviceJobProgressStatusAction->execute($deviceJobId);

        return $this->apiOk(['progressStatus' => $deviceJobProgressStatus]);
    }

    /**
     * Return a listing of the device job's device commands.
     *
     * @param Request $request
     * @param FilterDataTableDeviceJobDeviceCommandsAction $filterDataTableDeviceJobDeviceCommandsAction
     * @param string $deviceJobId
     * @return JsonResponse
     */
    public function deviceJobDeviceCommandsIndex(
        Request $request,
        FilterDataTableDeviceJobDeviceCommandsAction $filterDataTableDeviceJobDeviceCommandsAction,
        string $deviceJobId
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceJobId'] = $deviceJobId;

        $deviceCommands = $filterDataTableDeviceJobDeviceCommandsAction->execute($data);

        return $this->apiOk(['deviceCommands' => new DeviceCommandCollectionPagination($deviceCommands)]);
    }
}
