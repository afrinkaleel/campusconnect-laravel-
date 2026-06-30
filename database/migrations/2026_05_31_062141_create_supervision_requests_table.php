<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supervision_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->enum('status', ['pending','approved','rejected'])
                  ->default('pending');
            $table->timestamps();
            $table->foreign('project_id')
                  ->references('project_id')->on('projects')->cascadeOnDelete();
            $table->foreign('lecturer_id')
                  ->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('supervision_requests'); }
};