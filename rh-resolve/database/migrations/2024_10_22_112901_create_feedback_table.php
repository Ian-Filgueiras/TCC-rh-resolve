<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('texto'); 
            $table->timestamp('data_feedback')->useCurrent(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
