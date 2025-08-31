<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Create booking related permissions
        $insert_data = [['name' => 'crud_all_bookings',
                                'guard_name' => 'web'
                            ],
                            ['name' => 'crud_own_bookings',
                                'guard_name' => 'web'
                            ],
                        ];
        foreach ($insert_data as $data) {
            Permission::create($data);
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->integer('waiter_id')->unsigned()->nullable();
            $table->integer('table_id')->unsigned()->nullable();
            $table->integer('correspondent_id')->nullable();
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business');
            $table->integer('location_id')->unsigned();
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('booking_status');
            $table->text('booking_note')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
