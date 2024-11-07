<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notebooks', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->date('birth_date')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notebooks');
    }
};
