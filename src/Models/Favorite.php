<?php

namespace Aabosham\Favoritable\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    /**
     * Fillable fields for a favorite.
     *
     * @var array
     */
    protected $fillable = ['user_id'];
}
