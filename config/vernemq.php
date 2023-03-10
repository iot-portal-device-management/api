<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

return [

    'webhook_header' => 'vernemq-hook',

    'hooks' => [
        'auth_on_register' => 'auth_on_register',
        'auth_on_subscribe' => 'auth_on_subscribe',
        'auth_on_publish' => 'auth_on_publish',
        'on_register' => 'on_register',
        'on_publish' => 'on_publish',
        'on_subscribe' => 'on_subscribe',
        'on_unsubscribe' => 'on_unsubscribe',
        'on_deliver' => 'on_deliver',
        'on_offline_message' => 'on_offline_message',
        'on_client_wakeup' => 'on_client_wakeup',
        'on_client_offline' => 'on_client_offline',
        'on_client_gone' => 'on_client_gone',
        'auth_on_register_m5' => 'auth_on_register_m5',
        'on_auth_m5' => 'on_auth_m5',
        'auth_on_subscribe_m5' => 'auth_on_subscribe_m5',
        'auth_on_publish_m5' => 'auth_on_publish_m5',
        'on_register_m5' => 'on_register_m5',
        'on_publish_m5' => 'on_publish_m5',
        'on_subscribe_m5' => 'on_subscribe_m5',
        'on_unsubscribe_m5' => 'on_unsubscribe_m5',
        'on_deliver_m5' => 'on_deliver_m5',
    ],

];
