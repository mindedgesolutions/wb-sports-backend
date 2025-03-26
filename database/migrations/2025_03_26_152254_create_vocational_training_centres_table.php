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
        Schema::create('vocational_training_centres', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('nameOfcentre');
            $table->string('Address');
            $table->string('Phone');
            $table->string('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocational_training_centres');
    }
};
