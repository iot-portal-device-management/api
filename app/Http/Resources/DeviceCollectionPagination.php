<?php

namespace App\Http\Resources;

class DeviceCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceResource::class;
}
