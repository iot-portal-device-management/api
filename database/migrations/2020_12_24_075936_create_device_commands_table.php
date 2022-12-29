<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
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
        Schema::create('device_commands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('payload')->nullable();
            $table->uuid('device_command_type_id');
            $table->uuid('device_command_status_id');
            $table->uuid('device_command_error_type_id')->nullable();
            $table->uuid('device_job_id')->nullable();
            $table->string('job_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('device_command_type_id')->references('id')->on('device_command_types')->onDelete('cascade');
            $table->foreign('device_command_status_id')->references('id')->on('device_command_statuses')->onDelete('cascade');
            $table->foreign('device_command_error_type_id')->references('id')->on('device_command_error_types')->onDelete('cascade');
            $table->foreign('device_job_id')->references('id')->on('device_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_commands');
    }
};
