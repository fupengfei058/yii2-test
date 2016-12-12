<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1>Contestants</h1>
<ul>
<?php foreach ($contestants as $contestant): ?>
    <li>
        <?= Html::encode("{$contestant->contestantName} ({$contestant->desc})") ?>:
        <?= $contestant->voteCount ?>
    </li>
<?php endforeach; ?>
</ul>

<?= LinkPager::widget(['pagination' => $pagination]) ?>