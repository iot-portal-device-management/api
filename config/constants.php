<?php

use App\Models\DeviceEventType;

return [

    'base_device_command_type_records' => [
        ['name' => 'OTA', 'method_name' => 'triggerota'],
        ['name' => 'AOTA', 'method_name' => 'triggeraota'],
        ['name' => 'FOTA', 'method_name' => 'triggerfota'],
        ['name' => 'SOTA', 'method_name' => 'triggersota'],
        ['name' => 'COTA', 'method_name' => 'triggerconfig'],
        ['name' => 'SHUTDOWN', 'method_name' => 'shutdown_device'],
        ['name' => 'REBOOT', 'method_name' => 'reboot_device'],
        ['name' => 'DECOMMISSION', 'method_name' => 'decommission_device'],
        ['name' => 'FILE_UPLOAD', 'method_name' => 'file_upload'],
    ],

    'base_device_event_type_records' => [
        ['name' => DeviceEventType::TYPE_PROPERTY],
        ['name' => DeviceEventType::TYPE_TELEMETRY],
    ],

    // TODO: remove this cause not using
    'commands' => [
        'OTA' => [
            'method_name' => 'triggerota',
            'configuration_map' => [],
        ],
        'AOTA' => [
            'method_name' => 'triggeraota',
            'configuration_map' => [
                'app' => 'app',
                'command' => 'cmd',
                'tag' => 'containerTag',
                'reboot' => 'deviceReboot',
                'fetch_link' => 'fetch',
                'signature' => 'signature',
                'version' => 'version',
                'server_username' => 'username',
                'server_password' => 'password',
                'docker_registry' => 'dockerRegistry',
                'docker_username' => 'dockerUsername',
                'docker_password' => 'dockerPassword',
                'docker_compose_file' => 'file',
            ],
        ],
        'FOTA' => [
            'method_name' => 'triggerfota',
            'configuration_map' => [
                'bios_version' => 'biosversion',
                'fetch_link' => 'fetch',
                'manufacturer' => 'manufacturer',
                'path' => 'path',
                'product' => 'product',
                'release_date' => 'releasedate',
                'signature' => 'signature',
                'tool_options' => 'tooloptions',
                'vendor' => 'vendor',
                'server_username' => 'username',
                'server_password' => 'password',
            ],
        ],
        'SOTA' => [
            'method_name' => 'triggersota',
            'configuration_map' => [
                'command' => 'cmd',
                'fetch_link' => 'fetch',
                'log_to_file' => 'log_to_file',
                'username' => 'username',
                'password' => 'password',
            ],
        ],
        'COTA' => [
            'method_name' => 'triggercota',
            'configuration_map' => [
                'command' => 'cmd',
                'fetch_link' => 'fetch',
                'configurations' => 'path',
                'signature' => 'signature',
            ],
        ],
        'SHUTDOWN' => [
            'method_name' => 'shutdown_device',
            'configuration_map' => [],
        ],
        'REBOOT' => [
            'method_name' => 'reboot_device',
            'configuration_map' => [],
        ],
        'DECOMMISSION' => [
            'method_name' => 'decommission_device',
            'configuration_map' => [],
        ],
        'FILE_UPLOAD' => [
            'method_name' => 'file_upload',
            'configuration_map' => [],
        ],
    ],

];
