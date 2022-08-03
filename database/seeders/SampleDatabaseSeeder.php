<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceCommand;
use App\Models\DeviceCpuStatistic;
use App\Models\DeviceDiskStatistic;
use App\Models\DeviceEvent;
use App\Models\DeviceMemoryStatistic;
use App\Models\DeviceStatus;
use App\Models\DeviceTemperatureStatistic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SampleDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $deviceStatuses = DeviceStatus::all()->all();

        for ($i = 0; $i < 1; $i++) {
            $user = User::factory()->create();

            $this->command->info('User ' . $i + 1 . ': Email: ' . $user->email);

            $deviceCategories = array();

            $deviceCategories[] = $user->deviceCategories()->create([
                'name' => 'UNCATEGORIZED',
            ]);

            $deviceCategories[] = $user->deviceCategories()->create([
                'name' => 'CCTV',
            ]);

            $deviceCategories[] = $user->deviceCategories()->create([
                'name' => 'ROUTER',
            ]);

            $deviceCategories[] = $user->deviceCategories()->create([
                'name' => 'DESKTOP',
            ]);

            $deviceCategories[] = $user->deviceCategories()->create([
                'name' => 'LAPTOP',
            ]);

            for ($x = 0; $x < 5; $x++) {
                $device = Device::factory()
                    ->for(Arr::random($deviceCategories))
                    ->for(Arr::random($deviceStatuses))
                    ->create();

                $currentTime = now();
                foreach (range(1, 2) as $day) {
                    foreach (range(1, 1440) as $minute) {

                        DeviceTemperatureStatistic::factory()
                            ->for($device)
                            ->state([
                                'created_at' => $currentTime,
                                'updated_at' => $currentTime,
                            ])
                            ->create();

                        DeviceCpuStatistic::factory()
                            ->for($device)
                            ->state([
                                'created_at' => $currentTime,
                                'updated_at' => $currentTime,
                            ])
                            ->create();

                        DeviceDiskStatistic::factory()
                            ->for($device)
                            ->state([
                                'created_at' => $currentTime,
                                'updated_at' => $currentTime,
                            ])
                            ->create();

                        DeviceMemoryStatistic::factory()
                            ->for($device)
                            ->state([
                                'created_at' => $currentTime,
                                'updated_at' => $currentTime,
                            ])
                            ->create();

                        $currentTime->subMinutes(5);
                    }
                }

                for ($a = 0; $a < 20; $a++) {
                    DeviceCommand::factory()
                        ->for(Arr::random($device->deviceCommandTypes->all()))
                        ->create();

                    DeviceEvent::factory()
                        ->for(Arr::random($device->deviceEventTypes->all()))
                        ->create();
                }
            }
        }
    }
}
