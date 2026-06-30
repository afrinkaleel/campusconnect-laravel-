<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id('leave_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->enum('leave_type', ['annual','sick','emergency']);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->enum('status', ['pending','approved','rejected'])
                  ->default('pending');
            $table->timestamps();
            $table->foreign('lecturer_id')
                  ->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('leave_requests'); }
};