<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->text('message')->after('id');
            $table->foreignId('employer_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->enum('feedback_type', ['Positive', 'Neutral', 'Negative']);
            $table->timestamps();
            $table->foreignId('freelancer_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
};