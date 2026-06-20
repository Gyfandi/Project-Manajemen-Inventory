<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Owner extends Model
{
    // Menggunakan nama tabel khusus sesuai PRD
    protected $table = 'owner';

    protected $fillable = [
        'user_id',
        'nama',
        'username',
        'email',
        'password',
        'no_telp',
        'alamat',
        'jabatan',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Boot method untuk otomatisasi audit trail (created_by & updated_by)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Relasi ke User akun login
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User pembuat (creator)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User pengubah terakhir (updater)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
