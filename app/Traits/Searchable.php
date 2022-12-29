<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

trait Searchable
{
    public static function sortableColumns(): array
    {
        return (new static())->sortableColumns;
    }

    public static function filterableColumns(): array
    {
        return (new static())->filterableColumns;
    }

    public static function isColumnSortable(string $column): bool
    {
        return in_array($column, static::sortableColumns());
    }

    public static function isColumnFilterable(string $column): bool
    {
        return in_array($column, static::filterableColumns());
    }
}
