<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;
use frontend\models\events\PostCreatedEvent;

class PostForm extends Model
{


    const MAX_DESCRIPTION_LENGHT = 1000;
    const EVENT_POST_CREATED = 'post_created';

    public $id;
    public $picture;
    public $description;

    private $user;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize()],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    /**
     * @param integer $id,
     * @param User $user
     */
    public function __construct($id, User $user)
    {
        $this->id = $id;
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);//po validacijos iskviecia si metoda
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
    }

    /**
     * Resize image if needed
     */
    public function resizePicture()
    {
        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);     //    /tmp/11ro51

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save();        //    /tmp/11ro51
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
//            echo '<pre>';
//            print_r($this);
//            echo '<pre>';die;


            $post = $this->findPost($this->id);//priklausomai nuo id, gauname Post - ar tuscia ar pilna
            $post->description = $this->description;//jo savybei suteikiam duomenį iš formos
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);//apdorojam ir irasom
            $post->user_id = $this->user->getId();
            if ($post->save(false)) {
                $event = new PostCreatedEvent();
                $event->user = $this->user;//is post creator
                $event->post = $post;//pakraunam duomenimis

                $this->trigger(self::EVENT_POST_CREATED, $event);//add to Feed
                return  true;
            }
        }
        return false;//po blogos validacijos
    }

    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }

    /**
     * @param null $id
     * @return Post models empty|Post models by id static
     */
    private function findPost($id = null)
    {
        if ($id) {
            return Post::findOne($id);//for update
        }
        return new Post();//for add new post
    }


}
