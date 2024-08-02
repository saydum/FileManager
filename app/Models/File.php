<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'directory_id',
        'user_id',
        'is_public',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function directory(): BelongsTo
    {
        return $this->belongsTo(Directory::class);
    }
}
