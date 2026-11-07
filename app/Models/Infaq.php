<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PENDING_VERIFICATION = 'pending_verification';

    public const STATUS_PAID = 'paid';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'transaction_code',
        'public_token',
        'idempotency_key',
        'donor_name',
        'is_anonymous',
        'amount',
        'message',
        'payment_method',
        'payment_status',
        'gateway_reference',
        'manual_confirmation_at',
        'proof_path',
        'paid_at',
        'verification_reference',
        'reviewed_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'amount' => 'integer',
            'manual_confirmation_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }
}
