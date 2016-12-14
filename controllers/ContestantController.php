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
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,
                'variations' => [
                    Yii::$app->language,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM vote_contestant',
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $model = new Contestant();
        $query = $model->find();
        $pagination = new Pagination([
            'defaultPageSize' => 3,
            'totalCount' => $query->count(),
        ]);
        
        $contestants = $query->orderBy('sortNum')
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
        if(Yii::$app->request->isPost && $contestant->load(Yii::$app->request->post())) {
            $contestant->picList = UploadedFile::getInstance($contestant, 'picList');
            if ($contestant->upload()) {//Yii::$app->basePath.
                $contestant->picList = json_encode(Yii::$app->basePath . '/runtime/uploads/' . $contestant->picList->baseName . '.' . $contestant->picList->extension);
                if ($contestant->save()) {
                    return $this->redirect('./index.php?r=contestant/index');
                } else {
                    //var_dump($contestant->getErrors());
                }
            }
            return $this->render('add', ['model' => $contestant]);
        }
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
        //print_r($contestant3);exit;

        $sql = 'SELECT * FROM vote_contestant';
        $contestant4 = Contestant::findBySql($sql)->all();
        //var_dump($contestant4);
        
        $result = Contestant::getDb()->cache(function ($db) {
            return Contestant::find()->where(['id' => 1])
                ->where('sex = :sex',[':sex' => 1])
                ->orderBy('sortNum')
                ->asArray()
                ->all();
        });
        print_r($result);
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
    public function actionCookieTest(){
        $cookie['username'] = 'fja';
//         echo $cookie['username'];
        $session['password'] = '552';
//         echo $session['password'];
    }
}