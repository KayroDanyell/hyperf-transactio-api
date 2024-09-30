<?php

namespace App\Repository;

use App\DTO\TransferDTO;
use App\Model\Transfer;
use Hyperf\DbConnection\Db;
use Hyperf\Stringable\Str;

class TransferRepository extends AbstractRepository
{

    public function __construct(public Transfer $transferModel, Db $database)
    {
        parent::__construct($database);
    }

    public function save(TransferDTO $transfer) : TransferDTO
    {
        $transferSaved = $this->transferModel::create([
            'id' => Str::uuid(),
            'payer_id' => $transfer->getPayer()->getId(),
            'payee_id' => $transfer->getPayee()->getId(),
            'value' => $transfer->getValue()
        ]);
        $transfer->setId($transferSaved->id);
        return $transfer;
    }

    public function confirmTransfer(string $id)
    {
        $this->transferModel->find($id);

        $this->transferModel->setConfirmedAt(date('Y-m-d H:i:s'));

        return $this->transferModel->confirmed_at;
    }
}