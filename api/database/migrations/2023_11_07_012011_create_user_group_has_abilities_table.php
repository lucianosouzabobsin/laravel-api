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
        Schema::create('user_group_has_abilities', function (Blueprint $table) {
            $table->unsignedBigInteger('user_group_id');
            $table->unsignedBigInteger('ability_id');
            $table->primary(['user_group_id', 'ability_id']);
            $table->foreign('user_group_id')->references('id')->on('users_groups');
            $table->foreign('ability_id')->references('id')->on('abilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group_has_abilities');
    }
};
