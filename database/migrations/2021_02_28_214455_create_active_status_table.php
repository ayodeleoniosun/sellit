<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Api\V1\Models\ActiveStatus;

class CreateActiveStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $active_statuses = ['Active', 'Pending', 'Deleted', 'Suspended'];
        
        foreach ($active_statuses as $status) {
            ActiveStatus::create(['name' => $status]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::dropIfExists('active_status');
    }
}
