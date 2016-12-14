<?php 
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
//use yii\web\UploadedFile;

class Contestant extends ActiveRecord
{
    public $picList;

    public static function tableName() {
        return 'vote_contestant';
    }

    public function attributeLabels()
    {
        return [
            'contestantName' => '选手名称',
            'sex' => '性别',
            'mobile' => '手机号码',
            'picList' => '选手图片',
            'desc' => '选手描述',
        ];
    }

    public function rules()
    {
        return [
            [['itemId','sortNum','voteCount'],'safe'],
            [['contestantName', 'mobile','desc'], 'trim'],
            ['contestantName','required','message'=>'选手名称不能为空'],
            ['sex','required','message'=>'性别不能为空'],
            ['mobile','required','message'=>'手机号码不能为空'],
            ['mobile','number','message'=>'手机号码不合法'],
            ['picList','required','message'=>'请上传选手图片'],
            ['picList','file',
                'extensions'=>['png', 'jpg', 'gif','jpeg'],'wrongExtension'=>'只能上传{extensions}类型图片！',
                'maxSize'=>1024*1024*1024*2,'tooBig'=>'图片过大！',
                'skipOnEmpty'=>false,'uploadRequired'=>'请上传图片！',
                'checkExtensionByMimeType' => false,
                'message'=>'上传失败！'
            ],
            ['desc','required','message' => '选手描述不能为空'],
            ['desc','string', 'length' => [5,100],'tooLong'=>'{attribute}不能大于100个字符','tooShort'=>'{attribute}不能小于5个字符'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $dir = Yii::$app->basePath.'/runtime/uploads/';
            if(!is_dir($dir)){
                mkdir($dir,777);
            }
            $this->picList->saveAs($dir . $this->picList->baseName . '.' . $this->picList->extension);
            return true;
        } else {
            return false;
        }
    }
}