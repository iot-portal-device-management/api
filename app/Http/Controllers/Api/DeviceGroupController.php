<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceGroup\CreateDeviceGroupAction;
use App\Actions\DeviceGroup\DeleteDeviceGroupsAction;
use App\Actions\DeviceGroup\FilterDataTableDeviceGroupDevicesAction;
use App\Actions\DeviceGroup\FilterDataTableDeviceGroupsAction;
use App\Actions\DeviceGroup\FindDeviceGroupByIdAction;
use App\Actions\DeviceGroup\UpdateDeviceGroupAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDeviceGroupsRequest;
use App\Http\Requests\StoreDeviceGroupRequest;
use App\Http\Requests\UpdateDeviceGroupRequest;
use App\Http\Requests\ValidateDeviceGroupFieldsRequest;
use App\Http\Resources\DeviceGroupResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceGroupController
 * @package App\Http\Controllers\Api
 */
class DeviceGroupController extends Controller
{
//    /**
//     * DeviceGroupController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\DeviceGroup')->only(['index', 'options']);
//        $this->middleware('can:create,App\Models\DeviceGroup')->only('store');
//        $this->middleware('can:update,deviceGroup')->only('update');
//        $this->middleware('can:deleteMany,App\Models\DeviceGroup')->only('destroySelected');
//    }

    /**
     * Return a listing of the device group.
     *
     * @param Request $request
     * @param FilterDataTableDeviceGroupsAction $filterDataTableDeviceGroupAction
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableDeviceGroupsAction $filterDataTableDeviceGroupAction): JsonResponse
    {
        $deviceGroups = $filterDataTableDeviceGroupAction->execute($request->all());

        return $this->apiOk(['deviceGroups' => $deviceGroups]);
    }

    /**
     * Store a newly created device group in storage.
     *
     * @param StoreDeviceGroupRequest $request
     * @param CreateDeviceGroupAction $createDeviceGroupAction
     * @return JsonResponse
     */
    public function store(StoreDeviceGroupRequest $request, CreateDeviceGroupAction $createDeviceGroupAction): JsonResponse
    {
        $deviceGroup = $createDeviceGroupAction->execute($request->user(), $request->validated());

        return $this->apiOk(['deviceGroup' => new DeviceGroupResource($deviceGroup)]);
    }

    /**
     * Return the specified device group.
     *
     * @param FindDeviceGroupByIdAction $findDeviceGroupByIdAction
     * @param string $deviceGroupId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(FindDeviceGroupByIdAction $findDeviceGroupByIdAction, string $deviceGroupId): JsonResponse
    {
        $deviceGroup = $findDeviceGroupByIdAction->execute($deviceGroupId);

        $this->authorize('view', $deviceGroup);

        return $this->apiOk(['deviceGroup' => new DeviceGroupResource($deviceGroup)]);
    }

    /**
     * Update the specified device group in storage.
     *
     * @param UpdateDeviceGroupRequest $request
     * @param UpdateDeviceGroupAction $updateDeviceGroupAction
     * @param FindDeviceGroupByIdAction $findDeviceGroupByIdAction
     * @param string $deviceGroupId
     * @return JsonResponse
     */
    public function update(UpdateDeviceGroupRequest $request, UpdateDeviceGroupAction $updateDeviceGroupAction, FindDeviceGroupByIdAction $findDeviceGroupByIdAction, string $deviceGroupId): JsonResponse
    {
        $success = $updateDeviceGroupAction->execute($deviceGroupId, $request->validated());

        return $success
            ? $this->apiOk(['deviceGroup' => new DeviceGroupResource($findDeviceGroupByIdAction->execute($deviceGroupId))])
            : $this->apiInternalServerError('Failed to update device group');
    }

    /**
     * Remove the specified device groups from storage.
     *
     * @param DestroySelectedDeviceGroupsRequest $request
     * @param DeleteDeviceGroupsAction $deleteDeviceGroupsAction
     * @return JsonResponse
     */
    public function destroySelected(DestroySelectedDeviceGroupsRequest $request, DeleteDeviceGroupsAction $deleteDeviceGroupsAction): JsonResponse
    {
        $success = $deleteDeviceGroupsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete device groups');
    }

    /**
     * Return a listing of the device group devices.
     *
     * @param Request $request
     * @param FilterDataTableDeviceGroupDevicesAction $filterDataTableDeviceGroupDevicesAction
     * @param string $deviceGroupId
     * @return JsonResponse
     */
    public function deviceGroupDevicesIndex(Request $request, FilterDataTableDeviceGroupDevicesAction $filterDataTableDeviceGroupDevicesAction, string $deviceGroupId): JsonResponse
    {
        $deviceGroupDevices = $filterDataTableDeviceGroupDevicesAction->execute($deviceGroupId, $request->all());

        return $this->apiOk(['deviceGroupDevices' => $deviceGroupDevices]);
    }


    /**
     * Return device group options for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->deviceGroups();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['deviceGroups' => $query->getOptions()]);
    }

    /**
     * Validate device group options for user.
     *
     * @param ValidateDeviceGroupFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateDeviceGroupFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }
}
