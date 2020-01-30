<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */

$this->title = Yii::t('nsblog', 'Update Category: {name}', [
    'name' => $model->categoryContent->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('nsblog', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->categoryContent->name;
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allCategories' => $allCategories,
        'allPages' => $allPages,
    ]) ?>

</div>
