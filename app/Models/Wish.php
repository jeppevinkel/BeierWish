<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wish extends Model
{
    use HasFactory;

    public function owner()
    {
        return $this->wishList->owner;
    }

    public function wishList(): BelongsTo
    {
        return $this->belongsTo(WishList::class);
    }

    public function reservedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
