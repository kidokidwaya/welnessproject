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
 Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('user_name'); // task owner
        $table->string('task');      // description
        $table->enum('priority', ['low','medium','high'])->default('medium'); // priority
        $table->enum('mood_tag', ['happy','motivated','relaxed','calm','tired','okay','sad','angry','stressed'])->nullable(); // optional mood
        $table->boolean('completed')->default(false); // done or not
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
