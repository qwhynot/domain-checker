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
        Schema::create('domain_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['up', 'down', 'error']);
            $table->smallInteger('http_status_code')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('checked_at');
            $table->timestamp('created_at');

            $table->index('domain_id');
            $table->index('checked_at');
            $table->index(['domain_id', 'checked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_checks');
    }
};
