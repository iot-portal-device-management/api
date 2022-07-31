<?php

namespace App\Http\Resources;

class DeviceCategoryCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DeviceCategoryResource::class;
}
