<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Elouquent\UserRepository;
use App\Traits\Pagination;

class UserService
{
    use Pagination;

    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all(): Collection|array
    {
        return $this->userRepository->all();
    }

    public function store(array $request): Model
    {   
        $request['password'] = $this->generateSecurePassword(6);
        if(isset($request['password'])){ $request['password'] = Hash::make($request['password']); }
        return $this->userRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function findById(string $id): object
    {
        return $this->userRepository->findById($id);
    }

    public function findByField(string $field, string $value, string $returnType)
    {
        return $this->userRepository->findByField($field, $value, [], '', '', [], $returnType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(string $id, array $request): Model
    {
        if(isset($request['password'])){ $request['password'] = Hash::make($request['password']); }
        return $this->userRepository->update($id, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(string $id, array $request): string
    {
        $password = $this->generateSecurePassword(6);
        $request['password'] = Hash::make($password);
        $this->userRepository->update($id, $request);
        return $password;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): bool
    {
        return $this->userRepository->delete($id);
    }

    private function generateSecurePassword($length = 6): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }

}
