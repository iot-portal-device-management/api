<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Bootstrap the model with UUID.
     *
     * @return void
     */
    protected static function bootUuid()
    {
        /**
         * Listen for the creating event on the User model.
         * Sets the 'id' to a UUID using Str::uuid() on the instance being created
         */
        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), static::generateId());
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    public static function idExists($value): bool
    {
        return static::where('id', $value)->exists();
    }

    public static function generateId(): string
    {
        $id = Str::uuid()->toString();

        if (static::idExists($id)) {
            return static::generateId();
        }

        return $id;
    }
}
