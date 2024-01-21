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
        Schema::create('walk_ins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('table_id');
            $table->foreign('table_id')->references('id')->on('table_models')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('request_date');
            $table->time('request_time');
            $table->uuid('status_id');
            $table->foreign('status_id')->references('id')->on('reservation_statuses')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walk_ins');
    }
};
