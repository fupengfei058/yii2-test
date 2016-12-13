<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'add-form',
    'options' => ['class' => 'form-horizontal','enctype' => 'multipart/form-data'],
]) ?>
<?= $form->field($model, 'contestantName') ?>
<?= $form->field($model,'sex')->radioList(['1'=>'男','2'=>'女']) ?>
<?= $form->field($model, 'mobile') ?>
<?= $form->field($model, 'picList')->fileInput() ?>
<?= $form->field($model, 'desc')->textarea() ?>
<?= $form->field($model, 'id',['options' => ['class'=>'hidden']])->textInput(['default','value'=>2]) ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('添加', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>