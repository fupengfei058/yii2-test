<?php
//use \ApiTester;

class TestingApiCest
{
    public function _before(\ApiTester $I)
    {
    }

    public function _after(\ApiTester $I)
    {
    }

    // tests
    public function tryToTest(\ApiTester $I)
    {
        $I->sendPOST('/index.php?r=testing-api/my-test1', ['username' => 'admin', 'password' => 'admin']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'returnCode'    => 'integer|string',
            'returnMessage' => 'string',
        ]);
    }
}