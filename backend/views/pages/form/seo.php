<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model koperdog\yii2nsblog\models\Category
 */
?>

<?= $form->field($model, 'title', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'og_title', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'og_description')->textarea() ?>