<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace App\Providers;

use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceCpuStatistic;
use App\Models\DeviceCpuTemperatureStatistic;
use App\Models\DeviceDiskStatistic;
use App\Models\DeviceGroup;
use App\Models\DeviceJob;
use App\Models\DeviceMemoryStatistic;
use App\Models\SavedDeviceCommand;
use App\Models\User;
use App\Policies\DeviceCategoryPolicy;
use App\Policies\DeviceCpuStatisticPolicy;
use App\Policies\DeviceCpuTemperatureStatisticPolicy;
use App\Policies\DeviceDiskStatisticPolicy;
use App\Policies\DeviceGroupPolicy;
use App\Policies\DeviceJobPolicy;
use App\Policies\DeviceMemoryStatisticPolicy;
use App\Policies\DevicePolicy;
use App\Policies\SavedDeviceCommandPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Device::class => DevicePolicy::class,
        DeviceCategory::class => DeviceCategoryPolicy::class,
        DeviceGroup::class => DeviceGroupPolicy::class,
        DeviceJob::class => DeviceJobPolicy::class,
        SavedDeviceCommand::class => SavedDeviceCommandPolicy::class,
        DeviceCpuTemperatureStatistic::class => DeviceCpuTemperatureStatisticPolicy::class,
        DeviceCpuStatistic::class => DeviceCpuStatisticPolicy::class,
        DeviceDiskStatistic::class => DeviceDiskStatisticPolicy::class,
        DeviceMemoryStatistic::class => DeviceMemoryStatisticPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Password::defaults(function () {
            $rule = Password::min(16);

            return $this->app->isProduction()
                ? $rule->mixedCase()->symbols()->uncompromised()
                : $rule;
        });

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
