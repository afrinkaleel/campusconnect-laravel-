<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resource_bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('booking_date');
            $table->string('time_slot', 50);
            $table->enum('status', ['pending','approved','rejected','returned'])
                  ->default('pending');
            $table->timestamps();
            $table->foreign('resource_id')
                  ->references('resource_id')->on('resources')->cascadeOnDelete();
            $table->foreign('user_id')
                  ->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('project_id')
                  ->references('project_id')->on('projects')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('resource_bookings'); }
};