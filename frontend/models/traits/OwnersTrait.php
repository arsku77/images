<?php
/**
 * Created by PhpStorm.
 * User: arsku
 * Date: 2017.12.12
 * Time: 22:08
 */

namespace frontend\models\traits;
use frontend\models\User;

trait OwnersTrait
{
    /**
     * @param $userId
     * @return bool
     */
    public function isAddressee($userId): bool
    {
        return $userId === $this->user_id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isOwner(User $user): bool
    {
        return $user->getId() == $this->author_id;

    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isAuthor(User $user = null): bool
    {
        if ($user) {
            return $user->getId() == $this->user_id;
        }
        return false;
    }

}