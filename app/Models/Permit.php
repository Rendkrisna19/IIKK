<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // --- RELASI (HUBUNGAN ANTAR TABEL) ---
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(PermitType::class, 'permit_type_id');
    }

    public function approvals()
    {
        return $this->hasMany(PermitApproval::class);
    }

    public function checkLogs()
    {
        return $this->hasMany(PermitCheckLog::class);
    }

    // --- ACCESSORS (Trik agar view/frontend lama tidak error) ---
    
    public function getPermitTypeAttribute()
    {
        return $this->type ? strtolower($this->type->name) : 'tugas';
    }

    public function getApprovedByAttribute()
    {
        $approval = $this->approvals()->latest('approved_at')->first();
        return $approval ? $approval->approver_id : null;
    }

    public function getApprovedAtAttribute()
    {
        $approval = $this->approvals()->latest('approved_at')->first();
        return $approval ? $approval->approved_at : null;
    }

    public function getHodMessageAttribute()
    {
        $approval = $this->approvals()->latest('approved_at')->first();
        return $approval ? $approval->hod_message : null;
    }

    public function approver()
    {
        // Tetap dipertahankan dengan relasi palsu atau langsung ambil dari approval
        $approval = $this->approvals()->latest('approved_at')->first();
        if ($approval && $approval->approver_id) {
            return $this->belongsTo(User::class, 'id', 'id')->where('id', $approval->approver_id);
        }
        return $this->belongsTo(User::class, 'id', 'id')->where('id', -1); // Kosong
    }

    public function securityOut()
    {
        $log = $this->checkLogs()->where('check_type', 'OUT')->latest('log_time')->first();
        if ($log && $log->security_id) {
            return $this->belongsTo(User::class, 'id', 'id')->where('id', $log->security_id);
        }
        return $this->belongsTo(User::class, 'id', 'id')->where('id', -1);
    }

    public function securityIn()
    {
        $log = $this->checkLogs()->where('check_type', 'IN')->latest('log_time')->first();
        if ($log && $log->security_id) {
            return $this->belongsTo(User::class, 'id', 'id')->where('id', $log->security_id);
        }
        return $this->belongsTo(User::class, 'id', 'id')->where('id', -1);
    }

    public function getTimeOutAttribute()
    {
        $log = $this->checkLogs()->where('check_type', 'OUT')->latest('log_time')->first();
        return $log ? $log->log_time : null;
    }

    public function getTimeInAttribute()
    {
        $log = $this->checkLogs()->where('check_type', 'IN')->latest('log_time')->first();
        return $log ? $log->log_time : null;
    }

    public function getSecurityOutIdAttribute()
    {
        $log = $this->checkLogs()->where('check_type', 'OUT')->latest('log_time')->first();
        return $log ? $log->security_id : null;
    }

    public function getSecurityInIdAttribute()
    {
        $log = $this->checkLogs()->where('check_type', 'IN')->latest('log_time')->first();
        return $log ? $log->security_id : null;
    }

    public function getLateMinutesAttribute()
    {
        $log = $this->checkLogs()->where('check_type', 'IN')->latest('log_time')->first();
        return $log ? $log->late_minutes : null;
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