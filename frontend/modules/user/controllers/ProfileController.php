<?php

namespace frontend\modules\user\controllers;

use frontend\modules\user\models\forms\ProfileForm;
use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;


class ProfileController extends Controller
{

    public function actionView($nickname, $flagShowUpdateForm = false)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $limitPosts = Yii::$app->params['limitPostsInProfile'];
        $currentUser = Yii::$app->user->identity;
        $user = $this->findUser($nickname);
        $postList = $user->getPostList($limitPosts);
        $modelPicture = new PictureForm();
        $modelProfile = new ProfileForm($user, $flagShowUpdateForm);
//        echo '<pre>';
//        print_r($postItems);
//        echo '<pre>';die;

        return $this->render('view', [
            'user' => $user,
            'currentUser' => $currentUser,
            'modelPicture' => $modelPicture,
            'modelProfile' => $modelProfile,
            'postList' => $postList,
        ]);
    }


    public function actionUpdate($id, $flagShowUpdateForm = false)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentLoggedUser = Yii::$app->user->identity;
        $userNeedEdit = $this->findUserById($id);

        if (!$userNeedEdit -> equals($currentLoggedUser)){

            Yii::$app->user->logout();
            Yii::$app->session->setFlash('danger', 'Not your profile, not updated!');
            return $this->goHome();
        }

        $modelProfile = new ProfileForm($userNeedEdit, $flagShowUpdateForm);

        if ($flagShowUpdateForm || !$modelProfile->load(Yii::$app->request->post())){
           // $modelProfile->load(Yii::$app->request->post());
            return $this->redirect(['view', 'nickname' => $userNeedEdit->getNickname(), 'flagShowUpdateForm' => $flagShowUpdateForm]);
        }
        if ($modelProfile->load(Yii::$app->request->post()) && $modelProfile->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user','Profile updated!'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user','Profile not updated!'));
        }
        return $this->redirect(['view', 'nickname' => $userNeedEdit->getNickname(), 'flagShowUpdateForm' => false]);
    }

    /**
     * Handle profile image upload via ajax request
     */
    public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate()) {

            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture); // 15/27/30379e706840f951d22de02458a4788eb55f.jpg

            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * Handle profile image delete
     */
    public function actionDeletePicture()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        if ($currentUser->deletePicture()) {
            Yii::$app->session->setFlash('success', 'Picture deleted');
        } else {
            Yii::$app->session->setFlash('danger', 'Error occured');
        }
        return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
    }

    /**
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUser($nickname)
    {
        if ($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    public function actionSubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $user = $this->findUserById($id);

        $currentUser->followUser($user);

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }


    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $user = $this->findUser($id);

        $currentUser->unfollowUser($user);

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUserById($id)
    {
        if ($user = User::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

//    public function actionGenerate()
//    {
//        $faker = \Faker\Factory::create();
//
//        for ($i = 0; $i < 1000; $i++) {
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time,
//            ]);
//            $user->save(false);
//        }
//    }
}