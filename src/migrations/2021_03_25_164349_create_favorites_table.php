<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid');
                $table->unsignedBigInteger('user_id')->index();
                $table->morphs('favoritable');
                $table->tinyInteger('alarm')
                    ->default(0);
                $table->tinyInteger('isdeleted')
                    ->default(0);
                $table->unsignedBigInteger('deleted_by')
                    ->nullable();
                $table->dateTime('deleted_at')
                    ->nullable();

                $table->index([
                    'favoritable_id',
                    'favoritable_type'
                ], 'favorite_favoritable_id_favoritable_type_index');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
