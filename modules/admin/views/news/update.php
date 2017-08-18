<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Update post: ' . $model->title;
?>
<div class="news-update">

    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
        'model' => $model,
    ])?>

</div>
