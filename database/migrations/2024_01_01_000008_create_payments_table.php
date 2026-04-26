<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fine_id')->constrained('fines')->onDelete('restrict');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->foreignId('received_by')->constrained('users')->onDelete('restrict');
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('Cash'); // Cash, Bank, Mobile Money
            $table->string('bank_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
