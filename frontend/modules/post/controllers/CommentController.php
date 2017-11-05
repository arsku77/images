<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Post;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Comment;

/**
 * Default controller for the `post` module
 */
class CommentController extends Controller
{

    /**
     * create new comment
     */
    public function actionCreate($postId)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($postId);
        $model = new CommentForm(null, $post, $currentUser);

        if ($model->load(Yii::$app->request->post())) {
//            echo '<pre>';
//            print_r($model);
//            echo '<pre>';die;

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Comment created!');
                return $this->redirect(['default/view', 'id' => $postId]);
            }
        }

        return $this->redirect(['view', 'id' => $postId]);
    }
    /**
     * update comment
     */
    public function actionUpdate($id, $postId)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;

        $post = $this->findPost($postId);

        $model = new CommentForm($id, $post, $currentUser);

        if ($model->load(Yii::$app->request->post())&&$model->save()) {

                Yii::$app->session->setFlash('success', 'Comment updated!');
                return $this->redirect(['default/view', 'id' => $postId]);
        }

        return $this->redirect(['default/view', 'id' => $id]);
    }

    /**
     * delete comment by id
     */
    public function actionDelete($id)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $comment = Comment::findOne($id);

        $post = $this->findPost($comment->post_id);

        $currentUser = Yii::$app->user->identity;

        if ($comment->isAuthor($currentUser)||$post->isAuthor($currentUser)){

            if ($comment->delete()) {
                $post->unCommentRemToRedis($comment);
                Yii::$app->session->setFlash('success', 'Comment deleted!');
                return $this->redirect(['default/view', 'id' => $comment->post_id]);
            }

        }
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


}
