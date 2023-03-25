<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Providers;

use App\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Laravel\Sanctum\SanctumServiceProvider as ServiceProvider;

class SanctumServiceProvider extends ServiceProvider
{
    /**
     * Configure the Sanctum middleware and priority.
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function configureMiddleware(): void
    {
        $kernel = app()->make(Kernel::class);

        $kernel->prependToMiddlewarePriority(EnsureFrontendRequestsAreStateful::class);
    }
}
