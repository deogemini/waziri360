<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('constituency_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('project_type')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('village')->nullable();
            $table->string('funding_source')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('amount_spent', 15, 2)->nullable();
            $table->string('status')->default('kupangwa');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('contractor')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constituency_projects');
    }
};
