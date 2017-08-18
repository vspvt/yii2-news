<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationTemplate */

$this->title = 'Create Notification Template';
$this->params['breadcrumbs'][] = ['label' => 'Notification Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-template-create">

    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
        'model' => $model,
    ])?>

</div>
