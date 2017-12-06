<?php


namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;

class ProfileForm extends Model
{
    public $user;
    public $about;
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['parent_id', 'post_id', 'author_id'], 'integer'],
            //  [['parent_id'], 'integer'],

//            [['username'], 'required'],
//            [['about'], 'safe'],
            [['id'], 'safe'],
            [['about'], 'string', 'max' => Yii::$app->params['maxLengthProfileTextAboutSelf']],

        ];
    }

    /**
     * @param User $user
     * @param $config array
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->about = $user->about;
        $this->id = $user->id;
//        $this->about = $user->about;
//        parent::__construct($config);
    }

    /**
     * User save method
     * @return boolean
     */
    public function save() : bool
    {
        if ($this->validate()) {
            $userForUpdate = $this->user;
//            $userForUpdate = User::findOne($this->id);
          //  $userForUpdate->username = $this->user->username;
            $userForUpdate->about = $this->about;
            if ($userForUpdate->save(false,['about'])) {
                return true;
            }
            return false;
        }
    }

}