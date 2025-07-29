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
        Schema::create('uploaded_excel_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            // Assuming to store the file path in a string format
            $table->string('file_path');
            $table->string('uploaded_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_excel_files');
    }
};
