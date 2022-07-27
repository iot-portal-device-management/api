<?php

namespace App\Http\Resources;

class DeviceGroupCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceGroupResource::class;
}
