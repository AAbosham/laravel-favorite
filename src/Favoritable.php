<?php

namespace Aabosham\Favoritable;

use Aabosham\Favoritable\Models\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function totalFavoritesCount(): string
    {
        return $this->favorites()->where('isdeleted',0)->count();
    }

    public function totalFavoritesCountDigital(): string
    {
        $count = $this->totalFavoritesCount();

        if($count > (1000 * 1000)){
            $count_string = ($count / (1000 * 1000)).'M';
        } else if($count > 1000){
            $count_string = ($count / 1000).'K';
        } else {
            $count_string = $count;
        }

        return (string) $count_string;
    }

    public function scopeFavoritedBy($query, User $user)
    {
        return $query->whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('isdeleted', 0);
        });
    }

    public function isFavoritedBy($user = null)
    {
        if($user == null){
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        return $this->favorites()
            ->where('user_id', $user->id)
            ->where('isdeleted',0)
            ->exists();
    }

    public function scopeAlarmedBy($query, User $user)
    {
        return $query->whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('alarm', 1)
                ->where('isdeleted', 0);
        });
    }

    public function isAlarmedBy($user = null)
    {
        if($user == null){
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        return $this->favorites()
            ->where('user_id', $user->id)
            ->where('alarm', 1)
            ->where('isdeleted',0)
            ->exists();
    }

    public function favorite($user = null)
    {
        if($user == null){
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)
            ->where('isdeleted',0)
            ->exists()) {

            return true;
        } else {
            $favorite = new Favorite();
            $favorite->uuid = \DB::raw('UUID()');
            $favorite->user_id = $user->id;

            $favorite_save = $this->favorites()->save($favorite);

            if(!$favorite_save){
                return false;
            }

            return true;
        }

        return false;
    }

    public function unfavorite($user = null)
    {
        if($user == null){
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)
            ->where('isdeleted', 0)
            ->exists()) {

            $unfavorite = $this->favorites()
                ->where('user_id', '=', $user->id)
                ->where('isdeleted',0)
                ->where('favoritable_id', '=', $this->id)
                ->update([
                    'isdeleted' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => $user->id,
                ]);


            if(!$unfavorite){
                return false;
            }

            return true;
        }

        return true;
    }

    public function favoritesCount()
    {
        return $this->favorites()->count();
    }
}
