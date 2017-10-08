<?php
namespace frontend\controllers;

use frontend\models\Post;
use yii\web\Controller;
use frontend\models\User;
use Yii;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $limit = Yii::$app->params['feedPostLimit'];
        $feedItems = $currentUser->getFeed($limit);
        $post = new Post();
        return $this->render('index', [
            'feedItems' => $feedItems,
            'currentUser' => $currentUser,
            'post' => $post,
        ]);
    }



}
