<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentProof extends Model
{
    protected $fillable = [
        'payout_id',
        'proof_path',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the payout that owns this proof.
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }
}
