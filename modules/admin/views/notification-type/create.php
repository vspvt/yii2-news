<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationType */

$this->title = 'Create Notification Type';
$this->params['breadcrumbs'][] = ['label' => 'Notification Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-create">

    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
        'model' => $model,
    ])?>

</div>
