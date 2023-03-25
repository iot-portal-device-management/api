<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Http\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Middleware;

class EnsureFrontendRequestsAreStateful extends Middleware
{
    /**
     * Configure secure cookie sessions.
     *
     * @return void
     */
    protected function configureSecureCookieSessions(): void
    {
        config([
            'session.http_only' => true,
            'session.same_site' => 'none',
        ]);
    }
}
