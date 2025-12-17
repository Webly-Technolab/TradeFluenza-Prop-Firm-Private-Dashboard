<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payout_id' => $this->payout_id,
            'propfirm' => [
                'id' => $this->propfirm->id,
                'name' => $this->propfirm->name,
                'slug' => $this->propfirm->slug,
            ],
            'vendor' => $this->when($this->vendor, [
                'id' => $this->vendor?->id,
                'name' => $this->vendor?->name,
                'email' => $this->vendor?->email,
            ]),
            'beneficiary' => [
                'name' => $this->beneficiary_name,
                'email' => $this->beneficiary_email,
                'account_number' => $this->beneficiary_account_number,
                'bank' => $this->beneficiary_bank,
                'address' => $this->beneficiary_address,
            ],
            'amount' => $this->amount,
            'currency' => $this->currency,
            'formatted_amount' => $this->formatted_amount,
            'notes' => $this->notes,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'payment_proof_url' => $this->when(
                $this->payment_proof_path,
                asset('storage/' . $this->payment_proof_path)
            ),
            'rejection_reason' => $this->rejection_reason,
            'timestamps' => [
                'pending_at' => $this->pending_at?->toIso8601String(),
                'rejected_at' => $this->rejected_at?->toIso8601String(),
                'confirmed_at' => $this->confirmed_at?->toIso8601String(),
                'released_at' => $this->released_at?->toIso8601String(),
                'proof_uploaded_at' => $this->proof_uploaded_at?->toIso8601String(),
                'completed_at' => $this->completed_at?->toIso8601String(),
            ],
            'actors' => [
                'confirmed_by' => $this->when($this->confirmedBy, [
                    'id' => $this->confirmedBy?->id,
                    'name' => $this->confirmedBy?->name,
                ]),
                'rejected_by' => $this->when($this->rejectedBy, [
                    'id' => $this->rejectedBy?->id,
                    'name' => $this->rejectedBy?->name,
                ]),
                'released_by' => $this->when($this->releasedBy, [
                    'id' => $this->releasedBy?->id,
                    'name' => $this->releasedBy?->name,
                ]),
                'completed_by' => $this->when($this->completedBy, [
                    'id' => $this->completedBy?->id,
                    'name' => $this->completedBy?->name,
                ]),
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
