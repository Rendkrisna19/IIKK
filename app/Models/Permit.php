<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Tambahkan ini di atas

class Permit extends Model
{
    use HasFactory;

    // 1. Izinkan semua kolom diisi (kecuali ID & Timestamp)
    // Ini penting agar Permit::create() di controller tidak error.
    protected $guarded = ['id'];

    // --- RELASI (HUBUNGAN ANTAR TABEL) ---

    // Izin ini milik siapa? (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Siapa yang menyetujui? (HOD/Manager)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Siapa Security yang mencatat jam keluar?
    public function securityOut()
    {
        return $this->belongsTo(User::class, 'security_out_id');
    }

    // Siapa Security yang mencatat jam masuk?
    public function securityIn()
    {
        return $this->belongsTo(User::class, 'security_in_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}