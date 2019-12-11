<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="active main_section section">
        <?=$this->render('form/main', [
            'form'  => $form,
            'model' => $model,
            'allCategories' => $allCategories,
            'allPages' => $allPages,
        ]) ?>
    </div>
    
    <div class="seo_section section">
        <?=$this->render('form/seo', [
            'form'  => $form,
            'model' => $model
        ]) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
