<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeviceCategory\CreateDeviceCategoryAction;
use App\Actions\DeviceCategory\DeleteDeviceCategoriesAction;
use App\Actions\DeviceCategory\FilterDataTableDeviceCategoriesAction;
use App\Actions\DeviceCategory\FilterDataTableDeviceCategoryDevicesAction;
use App\Actions\DeviceCategory\FindDeviceCategoryByIdAction;
use App\Actions\DeviceCategory\UpdateDeviceCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedDeviceCategoriesRequest;
use App\Http\Requests\StoreDeviceCategoryRequest;
use App\Http\Requests\UpdateDeviceCategoryRequest;
use App\Http\Requests\ValidateDeviceCategoryFieldsRequest;
use App\Http\Resources\DeviceCategoryCollectionPagination;
use App\Http\Resources\DeviceCategoryResource;
use App\Http\Resources\DeviceCollectionPagination;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceCategoryController
 * @package App\Http\Controllers\Api
 */
class DeviceCategoryController extends Controller
{
//    /**
//     * DeviceCategoryController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\DeviceCategory')->only(['index', 'options']);
//        $this->middleware('can:create,App\Models\DeviceCategory')->only('store');
//        $this->middleware('can:update,deviceCategory')->only('update');
//        $this->middleware('can:deleteMany,App\Models\DeviceCategory')->only('destroySelected');
//    }

    /**
     * Return a listing of the device categories.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCategoriesAction $filterDataTableDeviceCategoriesAction
     * @return JsonResponse
     */
    public function index(
        Request $request,
        FilterDataTableDeviceCategoriesAction $filterDataTableDeviceCategoriesAction
    ): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $deviceCategories = $filterDataTableDeviceCategoriesAction->execute($data);

        return $this->apiOk(['deviceCategories' => new DeviceCategoryCollectionPagination($deviceCategories)]);
    }

    /**
     * Store a newly created device category in storage.
     *
     * @param StoreDeviceCategoryRequest $request
     * @param CreateDeviceCategoryAction $createDeviceCategoryAction
     * @return JsonResponse
     */
    public function store(
        StoreDeviceCategoryRequest $request,
        CreateDeviceCategoryAction $createDeviceCategoryAction
    ): JsonResponse
    {
        $deviceCategory = $createDeviceCategoryAction->execute($request->user(), $request->validated());

        return $this->apiOk(['deviceCategory' => new DeviceCategoryResource($deviceCategory)]);
    }

    /**
     * Display the specified device category.
     *
     * @param FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction
     * @param string $deviceCategoryId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(
        FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction,
        string $deviceCategoryId
    ): JsonResponse
    {
        $deviceCategory = $findDeviceCategoryByIdAction->execute($deviceCategoryId);

        $this->authorize('view', $deviceCategory);

        return $this->apiOk(['deviceCategory' => new DeviceCategoryResource($deviceCategory)]);
    }

    /**
     * Update the specified device category in storage.
     *
     * @param UpdateDeviceCategoryRequest $request
     * @param UpdateDeviceCategoryAction $updateDeviceCategoryAction
     * @param FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction
     * @param string $deviceCategoryId
     * @return JsonResponse
     */
    public function update(
        UpdateDeviceCategoryRequest $request,
        UpdateDeviceCategoryAction $updateDeviceCategoryAction,
        FindDeviceCategoryByIdAction $findDeviceCategoryByIdAction,
        string $deviceCategoryId
    ): JsonResponse
    {
        $success = $updateDeviceCategoryAction->execute($deviceCategoryId, $request->validated());

        return $success
            ? $this->apiOk(['deviceCategory' => new DeviceCategoryResource($findDeviceCategoryByIdAction->execute($deviceCategoryId))])
            : $this->apiInternalServerError('Failed to update device category.');
    }

    /**
     * Remove the specified device categories from storage.
     *
     * @param DestroySelectedDeviceCategoriesRequest $request
     * @param DeleteDeviceCategoriesAction $deleteDeviceCategoriesAction
     * @return JsonResponse
     */
    public function destroySelected(
        DestroySelectedDeviceCategoriesRequest $request,
        DeleteDeviceCategoriesAction $deleteDeviceCategoriesAction
    ): JsonResponse
    {
        $success = $deleteDeviceCategoriesAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete device categories');
    }

    /**
     * Return a listing of the device group devices.
     *
     * @param Request $request
     * @param FilterDataTableDeviceCategoryDevicesAction $filterDataTableDeviceCategoryDevicesAction
     * @param string $deviceCategoryId
     * @return JsonResponse
     */
    public function deviceCategoryDevicesIndex(
        Request $request,
        FilterDataTableDeviceCategoryDevicesAction $filterDataTableDeviceCategoryDevicesAction,
        string $deviceCategoryId
    ): JsonResponse
    {
        $data = $request->all();
        $data['deviceCategoryId'] = $deviceCategoryId;

        $devices = $filterDataTableDeviceCategoryDevicesAction->execute($data);

        return $this->apiOk(['devices' => new DeviceCollectionPagination($devices)]);
    }

    /**
     * Return device category options for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->deviceCategories();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['deviceCategories' => $query->getOptions()]);
    }

    /**
     * Validate device category selection.
     *
     * @param ValidateDeviceCategoryFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateDeviceCategoryFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }
}
