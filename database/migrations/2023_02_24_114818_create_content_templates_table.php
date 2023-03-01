<?php

use App\Models\User;
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
        Schema::create('content_templates', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->nullable();
            $table->timestamps();
            $table->json('columns')->nullable();
            $table->json('prompts')->nullable();
            $table->bigInteger('max_tokens')->nullable();
            $table->string('csv_path')->nullable();
            $table->foreignIdFor(User::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_templates');
    }
};
