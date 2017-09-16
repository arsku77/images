<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

class PostForm extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;

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
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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


            $post = new Post();//sukuriam naują egzempliorių
            $post->description = $this->description;//jo savybei suteikiam duomenį iš formos
            $post->created_at = time();// šiai naujo egzemplioriaus savybei priskiriam dabartinę datą
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(false);
        }

    }

    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }

}