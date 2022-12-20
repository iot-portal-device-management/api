<?php

namespace App\Http\Resources;

class DeviceEventCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceEventResource::class;
}
