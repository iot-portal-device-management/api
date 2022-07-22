<?php

namespace App\Http\Resources;

class CommandHistoryCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = CommandHistoryResource::class;
}
