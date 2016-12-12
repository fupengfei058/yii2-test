<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Contestant;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ContestantController extends Controller
{
    public function actionIndex()
    {
        $query = Contestant::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        
        $contestants = $query->orderBy('voteCount')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        
        return $this->render('index', [
            'contestants' => $contestants,
            'pagination' => $pagination,
        ]);
    }

    public function actionAdd()
    {
        $contestant = new Contestant();
        if(Yii::$app->request->isPost && $contestant->load(Yii::$app->request->post())){
            $contestant->picList = UploadedFile::getInstance($contestant, 'picList');
            if($contestant->upload()){//Yii::$app->basePath.
                $contestant->picList = json_encode('/runtime/uploads/' . $this->picList->baseName . '.' . $this->picList->extension);
                $contestant->save();
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model' => $contestant]);

        $contestant->itemId = 1;
        $contestant->sortNum = rand(1,10);
        $contestant->contestantName = 'Qiang'.rand(1,100);
        $contestant->sex = rand(1,2);
        $contestant->mobile = rand(11111,99999);
        $contestant->desc = 'hahaha';
        $contestant->voteCount = rand(1,100);
        $contestant->createTime = time();
        $contestant->save();
    }

    public function actionSelect()
    {
        $contestant1 = Contestant::find()
            ->where('voteCount >= :voteCount',[':voteCount' => 50])
            ->orderBy('sortNum')
            ->asArray()
            ->all();
        //print_r($contestant1);exit;

        $contestant2 = Contestant::find()
            ->where(['sortNum' => 1])
            ->asArray()
            ->one();
        //print_r($contestant2);exit;

        $contestant3 = Contestant::find()->indexBy('sortNum')->asArray()->all();
        print_r($contestant3);exit;

        $sql = 'SELECT * FROM vote_contestant';
        $contestant4 = Contestant::findBySql($sql)->all();
        var_dump($contestant4);
    }

    public function actionUpdate()
    {
        $contestant1 = Contestant::find()
            ->where('contestantId = :contestantId',[':contestantId' => 4])
            ->one();
        $contestant1->contestantName = '666';
        if($contestant1->save()){
            echo 'ok';
        }
    }

    public function actionDelete()
    {
        $contestant = Contestant::find()->where('contestantId = :contestantId',[':contestantId' => 4])->one();
        if ($contestant == null){
            throw new NotFoundHttpException;
        }
        if ($contestant->delete()){
            echo 'success';
        }
    }
}