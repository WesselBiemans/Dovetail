<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'owner_id',
    ];

    /**
     * Get the owner of the server.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the server.
     */
    public function members(): HasMany
    {
        return $this->hasMany(ServerMember::class);
    }

    /**
     * Get the channels for the server.
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }
}
