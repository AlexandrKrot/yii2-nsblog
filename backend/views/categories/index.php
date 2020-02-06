<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use koperdog\yii2nsblog\models\Category;

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

    <?php Pjax::begin(); ?>
    
    <div class="section-justify">
        <div>
            <?= Html::a(Yii::t('nsblog', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="zone-section">
            <div class="domain-change">
                <?= koperdog\yii2sitemanager\widgets\local\DomainList::widget();?>
            </div>
            <div class="language-change">
                <?= koperdog\yii2sitemanager\widgets\local\LanguageList::widget();?>
            </div>
        </div>
    </div>
    
    <?=    \koperdog\yii2treeview\TreeView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchForm,
        'id' => 'blog-grid',
//        'collapse' => true,
        'depthRoot' => 1,
        'columns' => [
            [
                'class' => '\koperdog\yii2treeview\base\CheckboxColumn',
            ],
            'id',
            [
                'label' => 'name',
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    $anchor = str_repeat(' — ', $model->depth - 1).$model->categoryContent->name;
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
                'attribute' => 'author_name',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    return Html::a($model->author->username, yii\helpers\Url::to(['update', 'id' => $model->id]));
                }  
            ],
            [
                'label' => 'image',
                'attribute' => 'categoryContent.image',
                'format' => 'image',
            ],
            'position',
            [
                'attribute' => 'status',
                'filter' => Category::getStatuses(),
            ],
        ]
    ]);?>
    
    <?php Pjax::end(); ?>

</div>