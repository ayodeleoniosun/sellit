<?php

use App\Modules\Api\V1\Models\Bathroom;
use App\Modules\Api\V1\Models\Bedroom;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Symfony\Component\String\b;

class CreateOtherSortOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bathroom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('active_status')->references('id')->on('active_status');
        });

        Schema::create('bedroom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('active_status')->references('id')->on('active_status');
        });

        foreach (range(1, 10) as $value) {
            Bathroom::create(['value' => $value]);
            Bedroom::create(['value' => $value]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bathroom');
        Schema::dropIfExists('bedroom');
    }
}
