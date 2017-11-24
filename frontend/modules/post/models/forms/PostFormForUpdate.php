<?php

namespace frontend\modules\post\models\forms;

use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

class PostFormForUpdate extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;
    public $id;
    public $description;
    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    /**
     * @param integer $id = null,
     * @param User $user
     */
    public function __construct($id = null, User $user = null)
    {//$id = null if new form, if user Guest
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $post = Post::findOne($this->id);
            $post->description = $this->description;//jo savybei suteikiam duomenį iš formos
            return $post->save(false, ['description']);
        }
        return false;//po blogos validacijos
    }

}
