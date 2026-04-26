<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('release_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->string('form_number')->unique();
            $table->foreignId('authorized_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('issued_to')->constrained('vehicle_owners')->onDelete('restrict');
            $table->text('conditions_of_release')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('release_forms');
    }
};
