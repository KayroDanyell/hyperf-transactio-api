<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 */
class Wallet extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'Wallet';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'user_id', 'balance'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hasEnoughBalanceToWithdraw(int $value) : bool
    {
        return $this->balance >= $value;
    }
}
