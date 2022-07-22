<?php

namespace App\Http\Resources;

class EventHistoryCollectionPagination extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = EventHistoryResource::class;
}
