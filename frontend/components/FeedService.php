<?php

namespace frontend\components;

use frontend\models\Post;
use frontend\models\User;
use yii\base\Component;
use yii\base\Event;
use frontend\models\Feed;

/**
 * Feed component
 *
 * @author admin
 */
class FeedService extends Component
{

    /**
     * Add post to feed of author subscribers
     * @param \yii\base\Event $event
     */
    public function addToFeeds(Event $event)
    {

        /* @var $user User */
        $user = $event->getUser();
        /* @var $post Post */
        $post = $event->getPost();

        $followers = $user->getFollowers();

        foreach ($followers as $follower) {
            $feedItem = new Feed([
                'user_id' => $follower['id'],
                'author_id' => $user->id,
                'author_name' => $user->username,
                'author_nickname' => $user->getNickname(),
                'author_picture' => $user->getPicture(),
                'post_id' => $post->id,
                'post_filename' => $post->filename,
                'post_description' => $post->description,
                'post_created_at' => $post->created_at,
            ]);
            $feedItem->save();
        }
    }

}