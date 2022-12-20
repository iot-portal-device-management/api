<?php

namespace App\Http\Resources;

class SavedDeviceCommandCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = SavedDeviceCommandResource::class;
}
