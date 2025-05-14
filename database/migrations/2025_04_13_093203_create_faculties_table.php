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
    Schema::create('faculties', function (Blueprint $table) {
        $table->id('FacultyCode')->primary();
        $table->string('FacultyName');
        $table->unsignedBigInteger('CampusId');
        $table->foreign('CampusId')->references('CampusId')->on('campuses')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};