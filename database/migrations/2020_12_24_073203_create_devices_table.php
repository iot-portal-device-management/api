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
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('bios_release_date')->nullable();
            $table->string('bios_vendor')->nullable();
            $table->string('bios_version')->nullable();
            $table->string('cpu')->nullable();
            $table->json('disk_information')->nullable();
            $table->string('os_information')->nullable();
            $table->string('system_manufacturer')->nullable();
            $table->string('system_product_name')->nullable();
            $table->unsignedBigInteger('total_memory')->nullable();
            $table->text('mqtt_password');
            $table->timestamp('last_seen')->nullable();
            $table->uuid('device_category_id');
            $table->uuid('device_status_id');
            $table->timestamps();

            $table->foreign('device_category_id')->references('id')->on('device_categories')->onDelete('cascade');
            $table->foreign('device_status_id')->references('id')->on('device_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};
