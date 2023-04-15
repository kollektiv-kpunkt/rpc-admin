<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supporters', function (Blueprint $table) {
            $table->json("data")->default(json_encode([
                "__" => bin2hex(random_bytes(16))
            ]))->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supporters', function (Blueprint $table) {
            $table->json("data")->default(json_encode("[]"))->change();
        });
    }
};
