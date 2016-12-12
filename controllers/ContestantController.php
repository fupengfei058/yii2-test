<?php
namespace app\controllers;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Contestant;

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
}