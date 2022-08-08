<?php

namespace App\Providers;

use App\Models\Device;
use App\Models\DeviceCategory;
use App\Models\DeviceGroup;
use App\Models\DeviceJob;
use App\Models\SavedDeviceCommand;
use App\Policies\DeviceCategoryPolicy;
use App\Policies\DeviceGroupPolicy;
use App\Policies\DeviceJobPolicy;
use App\Policies\DevicePolicy;
use App\Policies\SavedDeviceCommandPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Device::class => DevicePolicy::class,
        DeviceCategory::class => DeviceCategoryPolicy::class,
        DeviceGroup::class => DeviceGroupPolicy::class,
        DeviceJob::class => DeviceJobPolicy::class,
        SavedDeviceCommand::class => SavedDeviceCommandPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
