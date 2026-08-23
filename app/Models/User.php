<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_demo',
        'newsroom_slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_demo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (User $user): void {
            if (! $user->newsroom_slug) {
                $base = \Illuminate\Support\Str::slug($user->name) ?: 'newsroom-'.$user->id;
                $slug = $base;
                $suffix = 2;
                while (User::query()->where('newsroom_slug', $slug)->where('id', '<>', $user->id)->exists()) {
                    $slug = $base.'-'.$suffix++;
                }
                $user->forceFill(['newsroom_slug' => $slug])->saveQuietly();
            }

            foreach (['Go Public', 'Draft', 'Trash', 'My Press Releases'] as $order => $name) {
                DB::table('press_releases_folder')->insertOrIgnore([
                    'user_id' => $user->id,
                    'folder_name' => $name,
                    'order' => $order,
                    'type' => 'system',
                ]);
            }

            foreach ([['Starred', '#7f1d1d'], ['Social', '#334155'], ['Finance', '#a16207'], ['Politics', '#166534']] as [$name, $color]) {
                DB::table('press_releases_label')->insertOrIgnore([
                    'user_id' => $user->id,
                    'label_name' => $name,
                    'label_color' => $color,
                ]);
            }

            foreach (['Go Public', 'Draft', 'Trash', 'My News Rooms'] as $order => $name) {
                DB::table('news_rooms_folder')->insertOrIgnore([
                    'user_id' => $user->id,
                    'folder_name' => $name,
                    'order' => $order,
                    'type' => 'system',
                ]);
            }

            foreach ([['News', '#7f1d1d'], ['Focus', '#a16207'], ['Updates', '#166534']] as [$name, $color]) {
                DB::table('news_rooms_label')->insertOrIgnore([
                    'user_id' => $user->id,
                    'label_name' => $name,
                    'label_color' => $color,
                ]);
            }
        });
    }
}
