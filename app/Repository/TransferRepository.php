<?php

namespace App\Repository;

use App\DTO\TransferDTO;
use App\Model\Transfer;
use Hyperf\DbConnection\Db;

class TransferRepository extends AbstractRepository
{
    public Transfer $transferModel;
    public function __construct(Db $database)
    {
        parent::__construct($database);
    }

    public function save(TransferDTO $transfer) : TransferDTO
    {
        $transferSaved = $this->transferModel::create([
            'payer_id' => $transfer->getPayer()->getId(),
            'payee_id' => $transfer->getPayee()->getId(),
            'value' => $transfer->getValue()
        ]);
        $transfer->setId($transferSaved->id);
        return $transfer;
    }

    public function confirmTransfer(int $id)
    {
        $this->transferModel->find($id);

        $this->transferModel->setConfirmedAt(date('Y-m-d H:i:s'));

        $this->transferModel->save();
        return $this->transferModel->confirmed_at;
    }
}