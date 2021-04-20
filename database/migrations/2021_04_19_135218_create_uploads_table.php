<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string("file_name");
            $table->dateTime("uploaded_at");
            $table->string("file_size")->nullable();
            $table->string("file_ext")->nullable();
            $table->string("file_type")->nullable();
            $table->string("status")->nullable();
            $table->string("current")->default(0)->nullable();
            $table->string("total")->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
