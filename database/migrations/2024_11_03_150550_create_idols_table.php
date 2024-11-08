<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('idols', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stage_name')->nullable();
            $table->date('birthdate');
            $table->string('nationality');
            $table->date('debute_date');
            $table->string('position');
            $table->json('social_media')->nullable();
            $table->text('bio')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idols');
    }
};
