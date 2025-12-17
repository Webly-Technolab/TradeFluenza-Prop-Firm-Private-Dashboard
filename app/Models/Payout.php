<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payout extends Model
{
    protected $fillable = [
        'payout_id',
        'propfirm_id',
        'beneficiary_name',
        'beneficiary_email',
        'beneficiary_account_number',
        'beneficiary_bank',
        'ifsc_code',
        'upi_id',
        'payment_type',
        'amount',
        'currency',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'integer',
    ];

    /**
     * Get the propfirm user that owns this payout.
     */
    public function propfirm(): BelongsTo
    {
        return $this->belongsTo(User::class, 'propfirm_id');
    }

    /**
     * Get payment proofs for this payout.
     */
    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    /**
     * Status check methods
     */
    public function isPending(): bool { return $this->status === 0; }
    public function isRejected(): bool { return $this->status === 1; }
    public function isConfirmed(): bool { return $this->status === 2; }
    public function isReleased(): bool { return $this->status === 3; }
    public function hasProof(): bool { return $this->status === 4; }
    public function isFinalPayout(): bool { return $this->status === 5; }
    public function isCompleted(): bool { return $this->status === 6; }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            0 => 'Pending',
            1 => 'Rejected',
            2 => 'Confirmed',
            3 => 'Released for Payment',
            4 => 'Proof Uploaded',
            5 => 'Final Payout',
            6 => 'Payout Successful',
            default => 'Unknown',
        };
    }

    /**
     * Get status color for badge.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            0 => 'warning',
            1 => 'danger',
            2 => 'info',
            3 => 'primary',
            4 => 'secondary',
            5 => 'success',
            6 => 'success',
            default => 'secondary',
        };
    }

    /**
     * Transition methods
     */
    public function reject(string $reason): bool
    {
        if ($this->status !== 0) return false;
        
        return $this->update([
            'status' => 1,
            'rejection_reason' => $reason,
        ]);
    }

    public function confirm(): bool
    {
        if ($this->status !== 0) return false;
        
        return $this->update(['status' => 2]);
    }

    public function release(): bool
    {
        if ($this->status !== 2) return false;
        
        return $this->update(['status' => 3]);
    }

    public function markProofUploaded(): bool
    {
        if ($this->status !== 3) return false;
        
        return $this->update(['status' => 4]);
    }

    public function markFinalPayout(): bool
    {
        if ($this->status !== 4) return false;
        
        return $this->update(['status' => 5]);
    }

    public function complete(): bool
    {
        if ($this->status !== 5) return false;
        
        return $this->update(['status' => 6]);
    }
}
