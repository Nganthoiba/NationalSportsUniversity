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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('e.g edit_post, view_post. These data will remain fixed and not changeable.');
            $table->string('label')->unique()->nullable()->comment("Human readable permission name, e.g Edit Post, View Post");
            $table->string('description')->nullable()->comment("Detail about the permission");
            $table->string('group')->nullable()->comment('Optional grouping like Posts, Users, Settings');
            $table->boolean('enabled');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
