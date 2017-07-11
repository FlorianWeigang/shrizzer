<?php

namespace Shrizzer\Repositories;

use Shrizzer\Models\TempUser;
use Shrizzer\Models\User;

/**
 * Class UserRepository
 *
 * @package Shrizzer\Repositories
 */
class UserRepository
{
    /**
     * @param $id
     *
     * @return User
     */
    public function findById($id)
    {
        return User::find($id);
    }

    /**
     * @param $email
     *
     * @return User
     */
    public function findOrCreateByEmail($email)
    {
        return User::firstOrCreate(['email' => $email]);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user)
    {
        return $user->save();
    }

    /**
     * @return TempUser
     */
    public function getActiveUser()
    {
        $user = session('userObject', null);

        if ($user instanceof  TempUser === false) {
            $user = new TempUser();

            session(['userObject' => $user]);
        }

        return $user;
    }

    /**
     * @param TempUser $tempUser
     */
    public function saveTempUser(TempUser $tempUser)
    {
        session(['userObject' => $tempUser]);
    }
}