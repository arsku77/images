<?php
/**
 * Created by PhpStorm.
 * User: arsku
 * Date: 2017.09.21
 * Time: 08:49
 */

namespace frontend\components;


use yii\base\Component;
use yii\base\Event;

class FeedService extends Component
{
    public function addToFeeds(Event $event)
    {
        echo '<pre>';
        print_r($event);
        echo '<pre>';
        die('add post to feeds');
    }

}