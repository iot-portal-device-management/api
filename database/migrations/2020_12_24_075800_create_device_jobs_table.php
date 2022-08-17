<?php

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
        Schema::create('device_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('job_id')->nullable();
            $table->string('job_batch_id')->nullable();
            $table->uuid('device_job_error_type_id')->nullable();
            $table->uuid('device_job_status_id');
            $table->uuid('user_id');
            $table->uuid('device_group_id');
            $table->uuid('saved_device_command_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->foreign('device_job_error_type_id')->references('id')->on('device_job_error_types')->onDelete('cascade');
            $table->foreign('device_job_status_id')->references('id')->on('device_job_statuses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('device_group_id')->references('id')->on('device_groups')->onDelete('cascade');
            $table->foreign('saved_device_command_id')->references('id')->on('saved_device_commands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_jobs');
    }
};
