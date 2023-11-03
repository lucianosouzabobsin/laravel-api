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
        Schema::create('modules_actions_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('module_action_id');
            $table->string('name')->unique();
            $table->longText('description');
            $table->longText('link');
            $table->boolean('active')->default(true);

            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('module_action_id')->references('id')->on('modules_actions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules_actions_permissions');
    }
};
