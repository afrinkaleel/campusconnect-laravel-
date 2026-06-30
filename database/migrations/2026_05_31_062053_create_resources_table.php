<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resources', function (Blueprint $table) {
            $table->id('resource_id');
            $table->string('name', 100);
            $table->integer('quantity_total');
            $table->integer('quantity_available');
            $table->string('location', 100);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('resources'); }
};