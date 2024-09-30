<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\UserTypesEnum;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\DbConnection\Model\Model;

/**
 */
class User extends Model
{
    public bool $incrementing = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id','name', 'email', 'document', 'type'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'owner_id');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function isMerchant() : bool
    {
        return $this->type === UserTypesEnum::MERCHANT->value;
    }
}
