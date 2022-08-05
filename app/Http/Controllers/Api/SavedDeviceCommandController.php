<?php

namespace App\Http\Controllers\Api;

use App\Actions\SavedDeviceCommand\CreateSavedDeviceCommandAction;
use App\Actions\SavedDeviceCommand\DeleteMultipleSavedDeviceCommandsAction;
use App\Actions\SavedDeviceCommand\FilterDataTableSavedDeviceCommandsAction;
use App\Actions\SavedDeviceCommand\FindSavedCommandByIdOrUniqueIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySelectedSavedCommandRequest;
use App\Http\Requests\StoreSavedDeviceCommandRequest;
use App\Http\Requests\ValidateSavedCommandFieldsRequest;
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
     * Return a listing of the saved commands.
     *
     * @param Request $request
     * @param FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction
     * @return JsonResponse
     */
    public function index(Request $request, FilterDataTableSavedDeviceCommandsAction $filterDataTableSavedCommandsAction): JsonResponse
    {
        $savedCommands = $filterDataTableSavedCommandsAction->execute($request->all());

        return $this->apiOk(['savedCommands' => $savedCommands]);
    }

    /**
     * Store a newly created saved command in storage.
     *
     * @param StoreSavedDeviceCommandRequest $request
     * @param CreateSavedDeviceCommandAction $createSavedDeviceCommandAction
     * @return JsonResponse
     */
    public function store(StoreSavedDeviceCommandRequest $request, CreateSavedDeviceCommandAction $createSavedDeviceCommandAction): JsonResponse
    {
        $savedDeviceCommand = $createSavedDeviceCommandAction->execute($request->user(), $request->validated());

        return $this->apiOk(['savedDeviceCommand' => $savedDeviceCommand]);
    }

    /**
     * Return the specified saved command.
     *
     * @param FindSavedCommandByIdOrUniqueIdAction $findSavedCommandByIdOrUniqueIdAction
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(FindSavedCommandByIdOrUniqueIdAction $findSavedCommandByIdOrUniqueIdAction, string $id): JsonResponse
    {
        $savedCommand = $findSavedCommandByIdOrUniqueIdAction->execute($id);

        $this->authorize('view', $savedCommand);

        return $this->apiOk(['savedCommand' => $savedCommand]);
    }

    /**
     * Remove the specified saved commands from storage.
     *
     * @param DestroySelectedSavedCommandRequest $request
     * @param DeleteMultipleSavedDeviceCommandsAction $deleteMultipleSavedCommandsAction
     * @return JsonResponse
     */
    public function destroySelected(DestroySelectedSavedCommandRequest $request, DeleteMultipleSavedDeviceCommandsAction $deleteMultipleSavedCommandsAction): JsonResponse
    {
        $success = $deleteMultipleSavedCommandsAction->execute($request->ids);

        return $this->apiOk([], $success);
    }

    /**
     * Return the saved command options available for user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $query = Auth::user()->savedCommands();

        if ($request->has('name')) {
            $query->nameLike($request->name);
        }

        return $this->apiOk(['savedCommands' => $query->getOptions()]);
    }

    /**
     * Validate the saved command form fields.
     *
     * @param ValidateSavedCommandFieldsRequest $request
     * @return JsonResponse
     */
    public function validateField(ValidateSavedCommandFieldsRequest $request): JsonResponse
    {
        return $this->apiOk();
    }
}
