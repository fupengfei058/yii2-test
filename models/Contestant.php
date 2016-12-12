<?php 
namespace app\models;
use yii\db\ActiveRecord;

class Contestant extends ActiveRecord
{
    public static function tableName() {
        return 'vote_contestant';
    }
}