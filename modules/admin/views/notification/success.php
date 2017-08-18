<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?=Yii::$app->session->getFlash('success')?>
    </div>
<?php endif ?>
