<?php
namespace frontend\tests;

use frontend\tests\fixtures\UserFixture;

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


//    protected function _before()
//    {
//    }
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
        $user = $this->tester->grabFixture('users','user2');
        expect($user->getPostCount())->equals(3);
    }
//    public function example()
//    {
//
//    }
}