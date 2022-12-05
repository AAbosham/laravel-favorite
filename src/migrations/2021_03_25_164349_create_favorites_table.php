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
                $table->uuid('id')->primary();
                $table->foreignUuid('user_id')->index();
                $table->uuidMorphs('favoritable');
                $table->tinyInteger('alarm')
                    ->default(0);
                $table->foreignUuid('deleted_by')
                    ->nullable();
                $table->softDeletes('deleted_at');

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
