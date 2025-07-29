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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name',80)->comment('Name of the course');
            $table->string('short_form',80)->comment('Short Name of the course');
            $table->string('course_in_hindi',100)->comment('The Name of the course in hindi');
            $table->string('department_id', 50)->comment("The department wich the course belongs to");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
