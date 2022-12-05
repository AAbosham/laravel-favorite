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
        return $this->favorites()->count();
    }

    public function totalFavoritesCountDigital(): string
    {
        $count = $this->totalFavoritesCount();

        if ($count > (1000 * 1000)) {
            $count_string = ($count / (1000 * 1000)) . 'M';
        } else if ($count > 1000) {
            $count_string = ($count / 1000) . 'K';
        } else {
            $count_string = $count;
        }

        return (string) $count_string;
    }

    public function scopeFavoritedBy($query, $user)
    {
        return $query->whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function isFavorited($user = null)
    {
        return $this->isFavoritedBy($user);
    }

    public function isFavoritedBy($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function scopeAlarmedBy($query, $user)
    {
        return $query->whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('alarm', 1);
        });
    }

    public function isAlarmedBy($user = null)
    {
        if ($user == null) {
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

            ->exists();
    }

    public function favorite($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)

            ->exists()
        ) {

            return true;
        } else {
            $favorite = new Favorite();
            $favorite->user_id = $user->id;

            $favorite_save = $this->favorites()->save($favorite);

            if (!$favorite_save) {
                return false;
            }

            return true;
        }
    }

    public function unfavorite($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)

            ->exists()
        ) {

            $unfavorite = $this->favorites()
                ->where('user_id', '=', $user->id)

                ->where('favoritable_id', '=', $this->id)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => $user->id,
                ]);


            if (!$unfavorite) {
                return false;
            }

            return true;
        }

        return true;
    }

    public function alarm($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)

            ->exists()
        ) {

            $this->favorites()->update([
                'alarm' => 1
            ]);

            return true;
        } else {
            $favorite = new Favorite();
            $favorite->uuid = \DB::raw('UUID()');
            $favorite->user_id = $user->id;
            $favorite->alarm = 1;

            $favorite_save = $this->favorites()->save($favorite);

            if (!$favorite_save) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function unalarm($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        $user = auth()->user();

        if ($this->favorites()
            ->where('user_id', $user->id)

            ->exists()
        ) {

            $unfavorite = $this->favorites()
                ->where('user_id', $user->id)

                ->where('favoritable_id', $this->id)
                ->update([
                    'alarm' => 0,
                ]);


            if (!$unfavorite) {
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
