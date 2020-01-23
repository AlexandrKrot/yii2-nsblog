<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model koperdog\yii2nsblog\models\Category
 */
?>

<?= $form->field($model, 'categoryValue[title]', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'categoryValue[keywords]')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'categoryValue[description]')->textarea() ?>
<?= $form->field($model, 'categoryValue[og_title]', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'categoryValue[og_description]')->textarea() ?>