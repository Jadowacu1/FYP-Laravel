<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Campus (CompusId(PK), CompusName)
     */
    public function up()
{
    Schema::create('campuses', function (Blueprint $table) {
        $table->id('CampusId');
        $table->string('CampusName');
        $table->string('CampusLocation');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compuses');
    }
};