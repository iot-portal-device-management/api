<?php

namespace App\Http\Resources;

class DeviceCommandCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceCommandResource::class;
}
