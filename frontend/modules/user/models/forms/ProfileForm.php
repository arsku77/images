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
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['id'], 'safe'],
            [['about'], 'string', 'max' => Yii::$app->params['maxLengthProfileTextAboutSelf']],
        ];
    }

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->about = $user->about;
        $this->id = $user->id;
        $this->username = $user->username;
    }

    /**
     * User save method
     * @return boolean
     */
    public function save() : bool
    {
        if ($this->validate()) {
            $userForUpdate = $this->user;
            $userForUpdate->username = $this->username;
            $userForUpdate->about = $this->about;
            if ($userForUpdate->save(false,['username', 'about'])) {
                return true;
            }
            return false;
        }
    }

}