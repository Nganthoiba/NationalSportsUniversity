<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_change_requests', function (Blueprint $collection) {
            $collection->json('records_to_be_changed');//the desired fields to be changed
            $collection->string('registration_no');
            $collection->string('reason_of_change');
            $collection->string('requested_by');
            $collection->timestamp('date_of_request');
            $collection->string('status');
            $collection->string('reviewed_by')->nullable();
            $collection->timestamp('date_of_review')->nullable();
            // $collection->json('old_student_data')->nullable();
            // $collection->json('new_student_data')->nullable();
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_change_requests');
    }
};
