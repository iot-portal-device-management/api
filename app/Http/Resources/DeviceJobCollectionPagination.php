<?php

namespace App\Http\Resources;

class DeviceJobCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceJobResource::class;
}
