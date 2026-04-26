<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique(); // auto-generated
            $table->string('plate_number');
            $table->string('make'); // Toyota, Nissan, etc.
            $table->string('model');
            $table->string('color');
            $table->year('year')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('vehicle_type')->default('Car'); // Car, Truck, Motorcycle, etc.
            $table->enum('status', ['Impounded', 'Pending Payment', 'Cleared', 'Released', 'Auctioned'])->default('Impounded');
            $table->foreignId('owner_id')->constrained('vehicle_owners')->onDelete('restrict');
            $table->foreignId('impounded_by')->constrained('users')->onDelete('restrict');
            $table->string('impound_location'); // street/area where impounded
            $table->timestamp('impounded_at');
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
