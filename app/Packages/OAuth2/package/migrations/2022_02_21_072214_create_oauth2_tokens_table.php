<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('oauth2_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("user_id");
            $table->string("value");
            $table->timestamp("expired_at");
            $table->boolean("is_expired");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth2_tokens');
    }
};
