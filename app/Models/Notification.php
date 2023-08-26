<?php

namespace App\Models;

use App\Enums\NotificationCatEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'img_url',
        'category'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'title' => 'string',
        'content' => 'string',
        'img_url' => 'string',
        'category' => NotificationCatEnum::class
    ];

    /**
     * The users that belong to the notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['isClicked', 'status'])
            ->withTimestamps();
    }
}
