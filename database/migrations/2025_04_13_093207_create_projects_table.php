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
    Schema::create('projects', function (Blueprint $table) {
        $table->string('ProjectCode')->primary();
        $table->string('ProjectName');
        $table->text('ProjectProblems');
        $table->text('ProjectSolutions');
        $table->text('ProjectAbstract')->nullable();
        $table->longText('ProjectDissertation')->nullable();
        $table->longText('ProjectSourceCodes')->nullable();  
        $table->string('StudentRegNumber');
        $table->unsignedBigInteger('SupervisorId')->nullable(true);
        $table->unsignedBigInteger('DepartmentCode');
        $table->enum('status', ['Pending', 'Rejected', 'Approved'])->default('pending');
               

        $table->foreign('DepartmentCode')->references('DepartmentCode')->on('departments')->onDelete('cascade');
        $table->foreign('StudentRegNumber')->references('StudentRegNumber')->on('students')->onDelete('cascade');
        $table->foreign('SupervisorId')->references('SupervisorId')->on('supervisors')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};