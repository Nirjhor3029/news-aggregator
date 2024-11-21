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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content')->nullable();;
            $table->text('url')->nullable();
            $table->string('author')->nullable();
            $table->string('source')->nullable();;
            $table->string('category')->nullable();
            $table->timestamp('published_at')->nullable();;
            $table->text('image_url')->nullable();
            $table->integer('total_view')->default(0);
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
