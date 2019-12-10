<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'category_id')->label('Category')->dropDownList(
            koperdog\yii2nsblog\models\Category::getTree(), 
            ['prompt' => Yii::t('nsblog','No Parent'), 'class' => 'form-control']
        );?>

    <?= $form->field($model, 'preview_text')->widget(vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
            ],
            'buttons' => [
                'html',
                'formatting',
                'bold',
                'italic',
                'deleted',
                'unorderedlist',
                'orderedlist',
                'outdent',
                'indent',
                'image',
                'file',
                'link',
                'alignment',
                'horizontalrule'
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'full_text')->widget(vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
            ],
            'buttons' => [
                'html',
                'formatting',
                'bold',
                'italic',
                'deleted',
                'unorderedlist',
                'orderedlist',
                'outdent',
                'indent',
                'image',
                'file',
                'link',
                'alignment',
                'horizontalrule'
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'publish_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
