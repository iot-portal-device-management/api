<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait HasDeviceConnectionKey
{
    /**
     * Bootstrap the model with Device Connection Key.
     *
     * @return void
     */
    protected static function bootHasDeviceConnectionKey()
    {
        /**
         * Listen for the creating event on the user model.
         * Sets the 'device_connection_key' on the instance being created
         */
        static::creating(function ($model) {
            if (!isset($model->getAttributes()['device_connection_key'])) {
                $model->setAttribute('device_connection_key', static::generateEncryptedDeviceConnectionKey());
            }
        });
    }

    public static function deviceConnectionKeyExists($value)
    {
        return static::where('device_connection_key', $value)->exists();
    }

    public static function generateEncryptedDeviceConnectionKey(): string
    {
        $deviceConnectionKey = Crypt::encryptString(bin2hex(random_bytes(32)));

        if (static::deviceConnectionKeyExists($deviceConnectionKey)) {
            return static::generateEncryptedDeviceConnectionKey();
        }

        return $deviceConnectionKey;
    }

    public static function decryptDeviceConnectionKey($value): string
    {
        return Crypt::decryptString($value);
    }

    public function validateDeviceConnectionKey($value): bool
    {
        return $this->device_connection_key === $value;
    }

    public function getDeviceConnectionKeyAttribute($value): string
    {
        return static::decryptDeviceConnectionKey($value);
    }
}
