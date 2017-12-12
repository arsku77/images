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
    public $flagShowUpdateForm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['id'], 'safe'],
            [['flagShowUpdateForm'], 'safe'],
            [['about'], 'string', 'max' => Yii::$app->params['maxLengthProfileTextAboutSelf']],
        ];
    }

    /**
     * @param User $user
     * @param  $flagShowUpdateForm boolean
     */
    public function __construct(User $user, $flagShowUpdateForm = false)
    {
        $this->user = $user;
        $this->flagShowUpdateForm = $flagShowUpdateForm;
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
            $this->user->username = $this->username;
            $this->user->about = $this->about;
            if ($this->user->save(false,['username', 'about'])) {
                return true;
            }

        }
        return false;
    }

}