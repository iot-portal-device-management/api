<?php

namespace App\Http\Controllers\Api;

use App\Actions\SavedDeviceCommand\CreateSavedDeviceCommandAction;
use App\Actions\SavedDeviceCommand\DeleteSavedDeviceCommandsAction;
use App\Actions\SavedDeviceCommand\FilterDataTableSavedDeviceCommandsAction;
use App\Actions\SavedDeviceCommand\FindSavedDeviceCommandByIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedSavedDeviceCommandsRequest;
use App\Http\Requests\StoreSavedDeviceCommandRequest;
use App\Http\Requests\ValidateSavedCommandFieldsRequest;
use App\Http\Resources\SavedDeviceCommandCollectionPagination;
use App\Http\Resources\SavedDeviceCommandResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class SavedDeviceCommandController
 * @package App\Http\Controllers\Api
 */
class SavedDeviceCommandController extends Controller
{
//    /**
//     * SavedDeviceCommandController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('can:viewAny,App\Models\SavedDeviceCommand')->only(['index', 'options']);
//        $this->middleware('can:create,App\Models\SavedDeviceCommand')->only('store');
//        $this->middleware('can:deleteMany,App\Models\SavedDeviceCommand')->only('destroySelected');
//    }

    /**
     * Return a listing of the saved device commands.
     *
     * @param Request $request
     * @param FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction): JsonResponse
    {
        $data = $request->all();
        $data['userId'] = Auth::id();

        $savedDeviceCommands = $filterDataTableSavedCommandsAction->execute($data);

        return $this->apiOk(['savedDeviceCommands' => new SavedDeviceCommandCollectionPagination($savedDeviceCommands)]);
    }

    /**
     * Store a newly created saved device command in storage.
     *
     * @param StoreSavedDeviceCommandRequest $request
     * @param CreateSavedDeviceCommandAction $createSavedDeviceCommandAction
     * @return JsonResponse
     */
    public function store(StoreSavedDeviceCommandRequest $request, CreateSavedDeviceCommandAction $createSavedDeviceCommandAction): JsonResponse
    {
        $savedDeviceCommand = $createSavedDeviceCommandAction->execute($request->user(), $request->validated());

        return $this->apiOk(['savedDeviceCommand' => new SavedDeviceCommandResource($savedDeviceCommand)]);
    }

    /**
     * Return the specified saved device command.
     *
     * @param FindSavedDeviceCommandByIdAction $findSavedDeviceCommandByIdAction
     * @param string $savedDeviceCommandId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(FindSavedDeviceCommandByIdAction $findSavedDeviceCommandByIdAction, string $savedDeviceCommandId): JsonResponse
    {
        $savedDeviceCommand = $findSavedDeviceCommandByIdAction->execute($savedDeviceCommandId);

        $this->authorize('view', $savedDeviceCommand);

        return $this->apiOk(['savedDeviceCommand' => new SavedDeviceCommandResource($savedDeviceCommand)]);
    }

    /**
     * Remove the specified saved device commands from storage.
     *
     * @param DestroySelectedSavedDeviceCommandsRequest $request
     * @param DeleteSavedDeviceCommandsAction $deleteSavedDeviceCommandsAction
     * @return JsonResponse
     */
    public function destroySelected(DestroySelectedSavedDeviceCommandsRequest $request, DeleteSavedDeviceCommandsAction $deleteSavedDeviceCommandsAction): JsonResponse
    {
        $success = $deleteSavedDeviceCommandsAction->execute($request->ids);

        return $success
            ? $this->apiOk()
            : $this->apiInternalServerError('Failed to delete saved device commands');
    }

    /**
     * Return the saved device command options available for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->savedDeviceCommands();

        if ($request->has('name')) {
            $query->nameILike($request->name);
        }

        return $this->apiOk(['savedDeviceCommands' => $query->getOptions()]);
    }

    /**
     * Validate the saved device command form fields.
     *
     * @param ValidateSavedCommandFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateSavedCommandFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }
}
