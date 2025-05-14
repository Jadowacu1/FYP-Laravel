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
        Schema::create('hod', function (Blueprint $table) {
            $table->id('HodId');
            $table->string('Email');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('PhoneNumber');
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
        Schema::dropIfExists('hod');
    }
};