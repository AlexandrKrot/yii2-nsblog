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

    <?= $form->field($model, 'parent_id')->label('Parent')->dropDownList(
            koperdog\yii2nsblog\models\Category::getTree($model->id), 
            ['prompt' => Yii::t('nsblog','No Parent'), 'class' => 'form-control']
        );?>

    <?= $form->field($model, 'position')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

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
    
    <?= $form->field($model, 'addCategories')->widget(\kartik\select2\Select2::className(), [
        'data' => $allCategories,   
        'language' => 'ru',
        'options' => ['placeholder' => 'Select a category', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    
    <?= $form->field($model, 'addPages')->widget(\kartik\select2\Select2::className(), [
        'data' => $allPages,   
        'language' => 'ru',
        'options' => ['placeholder' => 'Select a page', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    
    <?= $form->field($model, 'rltCategories')->widget(\kartik\select2\Select2::className(), [
        'data' => $allCategories,   
        'language' => 'ru',
        'options' => ['placeholder' => 'Select a category', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    
    <?= $form->field($model, 'rltPages')->widget(\kartik\select2\Select2::className(), [
        'data' => $allPages,   
        'language' => 'ru',
        'options' => ['placeholder' => 'Select a page', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    
    <?= $form->field($model, 'access_read')->textInput() ?>

    <?= $form->field($model, 'publish_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
