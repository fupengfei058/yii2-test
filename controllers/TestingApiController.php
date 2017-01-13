<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class TestingApiController extends Controller
{
    public function actionMyTest1()
    {
        $post = Yii::$app->request->post();
        if(isset($post['username']) && isset($post['password'])){
            if(trim($post['username']) == 'admin' && $post['password'] == 'admin'){
                exit(json_encode(['returnCode'=>0,'returnMessage'=>'success']));
            }
        }
        exit(json_encode(['returnCode'=>-1,'returnMessage'=>'fail']));
    }
}
