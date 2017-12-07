<?php

namespace frontend\modules\post\models\forms;
use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use frontend\models\events\PostCreatedEvent;


class PostFormForUpdate extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;
    const EVENT_POST_CREATED = 'post_created';
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
    {//$id = null user = null, if user Guest can small view last news in \frontend\modules\post\views\default\index.php
        $this->id = $id;
        $this->user = $user;
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);

    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $post = Post::findOne($this->id);
            $post->description = $this->description;//jo savybei suteikiam duomenį iš formos
            $post->updated_at = time();//jo savybei suteikiam duomenį iš formos
             if ($post->save(false, ['description', 'updated_at' ])) {
                 $event = new PostCreatedEvent();
                 $event->user = $this->user;//is post creator
                 $event->post = $post;//pakraunam duomenimis

                 $this->trigger(self::EVENT_POST_CREATED, $event);//add to Feed
                 return true;
             }
        }
        return false;//po blogos validacijos
    }

}
