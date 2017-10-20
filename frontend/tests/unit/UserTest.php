<?php
namespace frontend\tests;

use frontend\tests\fixtures\UserFixture;
use Yii;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return ['users' => UserFixture::className()];
    }

    public function _before()
    {
        Yii::$app->setComponents([
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ],
        ]);
    }
//
//    protected function _after()
//    {
//    }

//    // tests
//    public function testSomeFeature()
//    {
//        $this->example();
//    }
    // next tests
    public function testGetNickNameOnNicknameEmpty()
    {
        $user = $this->tester->grabFixture('users','user1');
        expect($user->getNickname())->equals(1);
    }

    public function testGetNickNameOnNicknameNotEmpty()
    {
        $user = $this->tester->grabFixture('users','user2');
        expect($user->getNickname())->equals('catelyn');
    }

    public function testGetPostCount()
    {
        $user = $this->tester->grabFixture('users','user1');
        expect($user->getPostCount())->equals(3);
    }

    public function testFollowUser()
    {
        $user1 = $this->tester->grabFixture('users','user1');
        $user3 = $this->tester->grabFixture('users','user3');

        $user3 -> followUser($user1);//usris 3 seka, prenumeruoja userį 1

        $this->tester->seeRedisKeyContains('user:1:followers', 3);
        $this->tester->seeRedisKeyContains('user:3:subscriptions', 1);


        $this->tester->sendCommandToRedis('del', 'user:1:followers');
        $this->tester->sendCommandToRedis('del', 'user:3:subscriptions');

    }
//    public function example()
//    {
//
//    }
}