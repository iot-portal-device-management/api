<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use JsonSerializable;

class ResourceCollection extends BaseResourceCollection
{
    /**
     * Transform the resource into a JSON array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'currentPage' => $this->resource->currentPage(),
                'lastPage' => $this->resource->lastPage(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
                'perPage' => $this->resource->perPage(),
                'total' => $this->resource->total()
            ]
        ];
    }
}
