<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends BaseService
{
    /**
     * UserService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(object $dto): \Illuminate\Database\Eloquent\Model
    {
        return $this->repository->create($dto->data);
    }

    public function update(int|string $id, object $dto): bool
    {
        return $this->repository->update($id, $dto->data);
    }

    /**
     * Verifikasi Pertama: Pendaftaran email & biodata (identitas)
     * Data disubmit untuk direview oleh admin.
     * 
     * @param array $data Data biodata & email
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function submitIdentityVerification(array $data)
    {
        $data['status'] = 'pending_identity_review';
        return $this->repository->create($data);
    }

    /**
     * Verifikasi Kedua: Daftar ulang & upload berkas peserta
     * Berkas disubmit untuk direview kembali oleh admin.
     * 
     * @param int|string $id ID User
     * @param array $data Data berkas
     * @return bool
     */
    public function submitDocumentVerification(int|string $id, array $data): bool
    {
        $data['status'] = 'pending_document_review';
        return $this->repository->update($id, $data);
    }
}