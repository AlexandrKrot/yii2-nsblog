<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */
?>

<div class="tr">
    <div><?= Html::checkbox('selection[]', false, ['value' => $model->id])?></div>
    <div class="id"><?=$model->id?></div>
    <div class="name">
        <?=($model->depth >= 1 ? str_repeat('&nbsp;—&nbsp;&nbsp;', $model->depth) : '')
            .Html::a(Html::encode($model->name), ['/blog/category/update', 'id' => $model->id]);$model->name?>
    </div>
    <div class="url"><?=$model->url?></div>
    <div class="pages">1</div>
    <div class="author"><?=$model->author_id?></div>
    <div class="status"><?=$model->status?></div>
    <div class="action-column">
        <a href="<?=Url::to(['/blog/category/view', 'id' => $model->id])?>" title="View" aria-label="View" data-pjax="0">
            <span class="glyphicon glyphicon-eye-open"></span>
        </a> 
        <a href="<?=Url::to(['/blog/category/update', 'id' => $model->id])?>" title="Update" aria-label="Update" data-pjax="0">
            <span class="glyphicon glyphicon-pencil"></span>
        </a> 
        <a 
            href="<?=Url::to(['/blog/category/delete', 'id' => $model->id])?>" 
            title="Delete" 
            aria-label="Delete" 
            data-pjax="0" 
            data-confirm="Are you sure you want to delete this item?" 
            data-method="post"
        >
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    </div>
</div>