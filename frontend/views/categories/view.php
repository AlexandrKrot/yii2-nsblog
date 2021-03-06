<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */

$this->title = $model->categoryContent->name;

foreach($model->parents as $key => $category){
    $this->params['breadcrumbs'][] = ['url' => ['/blog/category', 'id' => $category->id], 'label' => $category->categoryContent->name];
}

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('nsblog', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('nsblog', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('nsblog', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'categoryContent.name',
            'url:url',
            'author_id',
            'status',
            'categoryContent.h1',
            'categoryContent.image',
            'categoryContent.preview_text:ntext',
            'categoryContent.full_text:ntext',
            'tree',
            'lft',
            'rgt',
            'depth',
            'position',
            'access_read',
            'categoryContent.domain_id',
            'categoryContent.language_id',
            'publish_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
