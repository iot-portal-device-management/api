<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_aota_configurations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('app');
            $table->string('command');
            $table->string('container_tag')->nullable();
            $table->string('device_reboot')->nullable();
            $table->string('fetch')->nullable();
            $table->string('signature')->nullable();
            $table->string('version')->nullable();
            $table->string('server_username')->nullable();
            $table->string('docker_registry')->nullable();
            $table->string('docker_username')->nullable();
            $table->string('docker_compose_file')->nullable();
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_aota_configurations');
    }
};
