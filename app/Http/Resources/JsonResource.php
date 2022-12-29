<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;
use Illuminate\Support\Str;
use JsonSerializable;

class JsonResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|JsonSerializable|Arrayable|null
     */
    public function toArray($request): array|JsonSerializable|Arrayable|null
    {
        return $this->encodeJson($this->resource);
    }

    /**
     * Encode a value to camelCase JSON
     *
     * @param mixed $value
     * @return array|string|null
     */
    public function encodeJson(mixed $value): array|string|null
    {
        if ($value instanceof Arrayable) {
            return $this->encodeArrayable($value);
        } else if (is_array($value)) {
            return $this->encodeArray($value);
        } else if (is_object($value)) {
            return $this->encodeArray((array)$value);
        }

        return $value;
    }

    /**
     * Encode a Arrayable
     *
     * @param Arrayable $arrayable
     * @return array
     */
    public function encodeArrayable(Arrayable $arrayable): array
    {
        $array = $arrayable->toArray();
        return $this->encodeJson($array);
    }

    /**
     * Encode an array
     *
     * @param array $array
     * @return array
     */
    public function encodeArray(array $array): array
    {
        $newArray = [];
        foreach ($array as $key => $val) {
            $newArray[Str::camel($key)] = $this->encodeJson($val);
        }

        return $newArray;
    }
}
