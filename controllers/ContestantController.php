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
        if(isset($_POST['Contestant'])){
            $contestant->attributes = $_POST['Contestant'];
            $file = UploadedFile::getInstance($contestant, 'picList');
            $fullPath = Yii::$app->basePath.'/runtime/uploads/' . time() . '.' . $file->getExtension();
            $file->saveAs($fullPath);
            $contestant->picList = json_encode($fullPath);
            if($contestant->save()){
                return $this->redirect('./index.php?r=contestant/index');
            }
        }
        return $this->render('add',['model' => $contestant]);
        exit;
        if(Yii::$app->request->isPost){
            $contestant->picList = UploadedFile::getInstance($contestant, 'picList');
            if($contestant->load(Yii::$app->request->post()) && $contestant->upload()){
                //$contestant->picList = 
                $contestant->itemId = 1;
                $contestant->sortNum = rand(1,10);
                $contestant->voteCount = 0;
                $contestant->createTime = time();
                if($contestant->save()){
                    return $this->redirect('./index.php?r=contestant/index');
                }else{
                    var_dump($contestant->getErrors());exit;
                }
            }else{
                var_dump($contestant->getErrors());exit;
            }
        }
        return $this->render('add',['model' => $contestant]);
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