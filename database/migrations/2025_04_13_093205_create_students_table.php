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
    Schema::create('students', function (Blueprint $table) {
        $table->string('StudentRegNumber')->primary();
        $table->string('FirstName');
        $table->string('LastName');
        $table->enum('Gender', ['Male', 'Female']);
        $table->string('Email')->unique();
        $table->string('PhoneNumber')->unique();
        $table->unsignedBigInteger('DepartmentCode');
        $table->foreign('DepartmentCode')->references('DepartmentCode')->on('departments')->onDelete('cascade')->onUpdate('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};