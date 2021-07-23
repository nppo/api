<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /** @var array */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @var array */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function likedProducts(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'likeable', 'likeables', 'user_id');
    }

    public function likedPeople(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'likeable', 'likeables', 'user_id');
    }

    public function likedProjects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'likeable', 'likeables', 'user_id');
    }

    public function likedParties(): MorphToMany
    {
        return $this->morphedByMany(Party::class, 'likeable', 'likeables', 'user_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
