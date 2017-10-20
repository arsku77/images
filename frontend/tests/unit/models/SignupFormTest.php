<?php
namespace frontend\tests\models;
use frontend\modules\user\models\SignupForm;



class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    public function testTrimUsername()
    {
        $model = new SignupForm([
            'username' => ' some_username ',
            'email' => 'some_email@example.com',
            'password' => '12345',
        ]);

        $model->signup();

        expect($model->username)
            ->equals('some_username');
//        sleep(15);
    }
}