<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // Movies table
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description');
            $table->string('movie_poster');
            $table->timestamps();
        });
        // Showrooms table
        Schema::create('showrooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('capacity');
            $table->integer('location');
            $table->timestamps();
        });

        // Shows table
        Schema::create('shows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('start_time');
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('showroom_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('showroom_id')->references('id')->on('showrooms');
            $table->timestamps();
        });

        // Pricing table
        Schema::create('pricings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('shows');
            $table->decimal('base_price', 8, 2);
            $table->decimal('vip_seat_premium', 8, 2);
            $table->timestamps();
        });

        // Seats table
        Schema::create('seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('shows');
            $table->string('name');
            $table->integer('row');
            $table->integer('column');
            $table->enum('seat_type',['vip','couple','super_vip']);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Bookings table
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('shows');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone');
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
        Schema::dropIfExists('seats');
        Schema::dropIfExists('pricings');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('showrooms');
        Schema::dropIfExists('movies');
    }
}
