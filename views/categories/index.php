<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel koperdog\yii2nsblog\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('nsblog', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

koperdog\yii2nsblog\AssetBundle::register($this);

$this->registerJsVar('sortUrl', yii\helpers\Url::to(['sort']));
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('nsblog', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>

    <?=    koperdog\yii2treeview\TreeView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchForm,
        'id' => 'grid',
//        'collapse' => true,
        'depthRoot' => 1,
        'columns' => [
            ['class' => '\koperdog\yii2treeview\base\CheckboxColumn'],
            'id',
            [
                'label' => 'name',
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    $anchor = str_repeat(' — ', $model->depth - 1).$model->name;
                    return Html::a($anchor, yii\helpers\Url::to(['update', 'id' => $model->id]));
                }
            ],
            [
                'label' => 'url',
                'attribute' => 'url',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    return Html::a($model->url, yii\helpers\Url::to(['update', 'id' => $model->id]));
                }
            ],
            [
                'label' => 'author',
                'filter' => true,
                'attribute' => 'author_id',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    return Html::a($model->author->username, yii\helpers\Url::to(['update', 'id' => $model->id]));
                }  
            ],
            'position',
            'status'
        ]
    ]);?>
    
    <?php Pjax::end(); ?>

</div>