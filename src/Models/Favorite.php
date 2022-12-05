<?php

namespace Aabosham\Favoritable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Favorite extends Model
{
    use HasUuids;
    use SoftDeletes;
    /**
     * Fillable fields for a favorite.
     *
     * @var array
     */
    protected $fillable = ['user_id'];

    public function favoritable()
    {
        return $this->morphTo();
    }
}
