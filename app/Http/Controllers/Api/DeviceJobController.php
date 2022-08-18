<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceJob\CreateDeviceJobAction;
use App\Actions\DeviceJob\DeleteMultipleDeviceJobsAction;
use App\Actions\DeviceJob\FilterDataTableDeviceJobsAction;
use App\Actions\DeviceJob\FindDeviceJobByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDeviceJobsRequest;
use App\Http\Requests\StoreDeviceJobRequest;
use App\Http\Requests\ValidateDeviceJobFieldsRequest;
use App\Http\Resources\DeviceJobResource;
use App\Jobs\ProcessDeviceJobJob;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function index(Request $request, FilterDataTableDeviceJobsAction $filterDataTableDeviceJobsAction): JsonResponse
    {
        $deviceJobs = $filterDataTableDeviceJobsAction->execute($request->all());

        return $this->apiOk(['deviceJobs' => $deviceJobs]);
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
        $deviceJob = $createDeviceJobAction->execute($request->user(), $request->validated());

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

        return $this->apiOk(['deviceJob' => $deviceJob]);
    }

    /**
     * Remove the specified device jobs from storage.
     *
     * @param DestroySelectedDeviceJobsRequest $request
     * @param DeleteMultipleDeviceJobsAction $deleteMultipleDeviceJobsAction
     * @return JsonResponse
     */
    public function destroySelected(DestroySelectedDeviceJobsRequest $request, DeleteMultipleDeviceJobsAction $deleteMultipleDeviceJobsAction): JsonResponse
    {
        $success = $deleteMultipleDeviceJobsAction->execute($request->ids);

        return $this->apiOk([], $success);
    }

    /**
     * Validate device jobs fields.
     *
     * @param ValidateDeviceJobFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateDeviceJobFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }
}
