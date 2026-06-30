<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('project_updates', function (Blueprint $table) {
            $table->id('update_id');
            $table->unsignedBigInteger('project_id');
            $table->text('update_text');
            $table->timestamps();
            $table->foreign('project_id')
                  ->references('project_id')->on('projects')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('project_updates'); }
};