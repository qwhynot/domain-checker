<?php

use App\Enums\CheckMethod;
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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('name')->nullable();
            $table->integer('check_interval')->default(5);
            $table->integer('check_timeout')->default(10);
            $table->enum('check_method', array_column(CheckMethod::cases(), 'value'))->default(CheckMethod::HEAD->value);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_active');
            $table->index(['is_active', 'last_checked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
