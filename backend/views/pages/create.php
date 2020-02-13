<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Page */

$this->title = Yii::t('nsblog', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('nsblog', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

koperdog\yii2nsblog\AssetBundle::register($this);
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allCategories' => $allCategories,
        'allPages' => $allPages,
    ]) ?>

</div>
