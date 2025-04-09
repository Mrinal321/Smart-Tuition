<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers'); // Assumes a 'teachers' table exists
            $table->string('course_name');
            $table->text('description')->nullable();
            $table->string('demo_class0')->nullable(); // URL or file path
            $table->string('demo_class1')->nullable();
            $table->string('demo_class2')->nullable();
            $table->string('resources_link')->nullable(); // URL to resources
            $table->decimal('original_fee', 10, 2); // e.g., 10000.00
            $table->decimal('updated_fee', 10, 2);
            $table->decimal('discount', 5, 2)->default(0); // e.g., 10.50%
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
