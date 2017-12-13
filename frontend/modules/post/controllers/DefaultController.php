<?php

namespace frontend\modules\post\controllers;

use frontend\modules\post\models\forms\PostFormForUpdate;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use frontend\models\Post;
use frontend\models\Feed;
use frontend\modules\post\models\forms\PostForm;
use frontend\modules\post\models\forms\CommentForm;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the create view list posts for the module
     * @return string
     */
    public function actionIndex()
    {
        /* @var $currentUser User */
        $max = Yii::$app->params['limitPostsInPostList'];
        $currentUserIdentity = Yii::$app->user->identity;
        $posts = Post::getPostsList($max);
//        $modelComment = new CommentForm(null, $post, $currentUser);

        return $this->render('index', [
            'posts' => $posts,
            'currentUserIdentity' => $currentUserIdentity,

        ]);

    }

    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionView($id)
    {
        /* @var $currentUser User */
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $model = new PostFormForUpdate($id, $currentUser);
        $post = $this->findPost($id);

        $modelComment = new CommentForm(null, $post, $currentUser);

        return $this->render('view', [
            'model' => $model,
            'post' => $post,
            'currentUser' => $currentUser,
            'modelComment' => $modelComment

        ]);
    }

    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        $userPostAuthor = Yii::$app->user->identity;
        $model = new PostForm(null, $userPostAuthor);

        if ($model->load(Yii::$app->request->post())) {

            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Post created!');
                return $this->redirect(['/user/profile/view', 'nickname' => $userPostAuthor->getNickname()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return Response
     */
    public function actionUpdate($id)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;


        $model = new PostFormForUpdate($id, $currentUser);

        if ($model->load(Yii::$app->request->post())&&$model->save()) {

            Yii::$app->session->setFlash('success', 'Post updated!');
            return $this->redirect(Yii::$app->request->referrer);
//            return $this->redirect(['default/index']);
        }

        return $this->redirect(['default/index']);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $currentUserLogged = Yii::$app->user->identity;
        $postModelForDelete = $this->findPost($id);
        if ($postModelForDelete &&
            $currentUserLogged->getId()==$postModelForDelete->user_id) {
            if ($postModelForDelete->delete()) {
                Yii::$app->session->setFlash('success', 'Post and its picture deleted');
            } else {
                Yii::$app->session->setFlash('danger', 'Error occured');
            }

        }
        return $this->redirect(['default/index']);
    }

    /**
     * Deletes an existing Feed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteFeed($id)
    {

        $currentUserLogged = Yii::$app->user->identity;
        $feedModelForDelete = $this->findFeedModel($id);
        if ($feedModelForDelete && $feedModelForDelete->isAddressee($currentUserLogged->getId())) {
            if ($feedModelForDelete->delete()) {
                Yii::$app->session->setFlash('success', 'Items is deleted');
            } else {
                Yii::$app->session->setFlash('danger', 'Error occured');
            }

        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);

        $post->unLike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }



    /**
     * @param integer $id
     * @return Post
     * @throws NotFoundHttpException
     */
    protected function findPost($id)
    {
        if ($post = Post::findOne($id)) {
            return $post;
        }
        throw new NotFoundHttpException();
    }

    public function actionComplain()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);

        if ($post->complain($currentUser)) {
            return [
                'success' => true,
                'text' => Yii::t('post', 'Post reported!'),
            ];
        }
        return [
            'success' => false,
            'text' => 'Error',
        ];
    }


    /**
     * Finds the Feed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findFeedModel($id)
    {
        if (($model = Feed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }





}
