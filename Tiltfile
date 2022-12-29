#
# Copyright (C) 2021-2023 Intel Corporation
# SPDX-License-Identifier: MIT
#

version_settings(constraint='>=0.22.2')

custom_build(
    'api-builder',
    'docker build -f dockerfiles/build/Dockerfile.production -t api-builder:development .',
    deps=['.'],
    tag='development',
)

custom_build(
    'iotportaldevicemanagement-api',
    'docker build -f dockerfiles/Dockerfile.production -t iotportaldevicemanagement-api:development .',
    deps=['.'],
    tag='development',
    live_update=[
        sync('.', '/var/www/html/'),
        sync('./dockerfiles/docker-entrypoint', '/usr/local/bin/docker-entrypoint'),
        run(
            'composer install --no-scripts --no-autoloader --ansi --no-interaction',
            trigger=['./composer.json', './composer.lock'],
        ),
    ],
    image_deps=['api-builder'],
)
