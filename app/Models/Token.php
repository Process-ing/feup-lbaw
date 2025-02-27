<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'token';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'administrator_id',
        'value',
        'creation_timestamp',
        'validity_timestamp',
    ];

    protected $casts = [
        'creation_timestamp' => 'datetime',
        'validity_timestamp' => 'datetime',
    ];

    public function isValid(): bool
    {
        return $this->validity_timestamp->isFuture();
    }

    public function account()
    {
        return $this->belongsTo(User::class, 'user_id')
            ?? $this->belongsTo(Administrator::class, 'administrator_id');
    }
}
