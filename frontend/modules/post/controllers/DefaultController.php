<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Comment;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {

            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Post created!');
                return $this->goHome();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionView($id)
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);
        $modelComment = new CommentForm(null, $post, $currentUser);

        return $this->render('view', [
            'post' => $post,
            'currentUser' => $currentUser,
            'modelComment' => $modelComment

        ]);
    }

    /**
     * create new comment
     */
    public function actionCreateComment($id)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);
        $model = new CommentForm(null, $post, $currentUser);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Comment created!');
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * create new comment
     */
    public function actionDeleteComment($id)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $comment = Comment::findOne($id);

        $post = $this->findPost($comment->post_id);

        $currentUser = Yii::$app->user->identity;

        if ($comment->isAuthor($currentUser)||$post->isAuthor($currentUser)){

            if ($comment->delete()) {
                Yii::$app->session->setFlash('success', 'Comment deleted!');
                return $this->redirect(['view', 'id' => $comment->post_id]);
            }

        }
    }

    /**
     * update comment
     */
    public function actionUpdateComment($id, $postId)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;

        $post = $this->findPost($postId);

        $model = new CommentForm($id, $post, $currentUser);

        if ($model->load(Yii::$app->request->post())&&$model->save()) {

                Yii::$app->session->setFlash('success', 'Comment updated!');
                return $this->redirect(['view', 'id' => $postId]);
        }

        return $this->redirect(['view', 'id' => $id]);
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
     * @return User
     * @throws NotFoundHttpException
     */
    private function findPost($id)
    {
        if ($user = Post::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

//    public function actionComments($id)
//    {
//        $model = new Comment();
//        $model->scenario = Comment::SCENARIO_COMMENT_REGISTER;
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->save()) {
//                Yii::$app->session->setFlash('comment saved', 'Thank you for contacting us. We will respond to you as soon as possible.');
//            } else {
//                Yii::$app->session->setFlash('error', 'There was an error saving your comment.');
//            }
//
//            return $this->refresh();
//
//        } else {
//            return $this->render('comment', [
//                'model' => $model,
//            ]);
//        }
//    }






}
