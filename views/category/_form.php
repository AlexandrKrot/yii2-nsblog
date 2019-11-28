<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class='form-group field-attribute-parentId'>
        <?= Html::label('Parent', 'parent', ['class' => 'control-label']);?>
        <?= Html::dropdownList(
            'Category[parentId]',
            $model->parentId,
            koperdog\yii2nsblog\models\Category::getTree($model->id),
            ['prompt' => 'No Parent (saved as root)', 'class' => 'form-control']
        );?>
    </div>

    <?= $form->field($model, 'position')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preview_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'full_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'access_show')->textInput() ?>

    <?= $form->field($model, 'publish_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
