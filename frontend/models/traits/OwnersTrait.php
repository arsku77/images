<?php
/**
 * Created by PhpStorm.
 * User: arsku
 * Date: 2017.12.12
 * Time: 22:08
 */

namespace frontend\models\traits;


trait OwnersTrait
{

    public function isAddressee($userId): bool
    {
        return $userId === $this->user_id;
    }

}