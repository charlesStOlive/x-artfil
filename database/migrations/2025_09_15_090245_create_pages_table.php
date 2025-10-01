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
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->json('meta_data')->nullable();
            $table->json('contents')->nullable();
            $table->json('statics')->nullable();
            $table->string('key_word')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_homepage')->default(false);
            $table->boolean('is_in_header')->default(false);
            $table->boolean('is_in_footer')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
