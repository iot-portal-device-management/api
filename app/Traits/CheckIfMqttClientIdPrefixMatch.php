<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Traits;

trait CheckIfMqttClientIdPrefixMatch
{
    protected function isMqttClientIdPrefixMatch(string $clientIdPrefix, string $clientId): bool
    {
        return str_starts_with($clientId, $clientIdPrefix);
    }
}
