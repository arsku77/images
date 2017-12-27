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
     * @param $postId
     * @return \yii\web\Response
     */
    public function actionCreate($postId)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($postId);
        $model = new CommentForm(null, $post, $currentUser);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            try {
                Yii::$app->session->setFlash('success', Yii::t('post','Comment created!'));
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error saving your comment.');
            }
            return $this->redirect(['default/view', 'id' => $postId]);
        }

        return $this->redirect(['default/view', 'id' => $postId]);
    }

    /**
     * @param $id
     * @param $postId
     * @return \yii\web\Response
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

            try {
                Yii::$app->session->setFlash('success', Yii::t('post','Comment updated!'));
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error updating your comment.');
            }
            return $this->redirect(['default/view', 'id' => $postId]);
        }

        return $this->redirect(['default/view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $comment = Comment::findOne($id);

        $post = $this->findPost($comment->post_id);

        $currentUser = Yii::$app->user->identity;

        if ($comment->isOwner($currentUser)||$post->isAuthor($currentUser)){

            if ($comment->delete()) {
                $post->unCommentRemToRedis($comment);
                Yii::$app->session->setFlash('success', 'Comment deleted!');
                return $this->redirect(['default/view', 'id' => $comment->post_id]);
            }

        }
    }


    /**
     * @param integer $id
     * @return \frontend\models\User
     * @throws yii\web\NotFoundHttpException
     */
    private function findPost($id)
    {
        if ($user = Post::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }


}
