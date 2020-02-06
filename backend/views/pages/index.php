<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use koperdog\yii2nsblog\models\Category;
use koperdog\yii2nsblog\models\Page;
use koperdog\yii2sitemanager\components\{
    Domains,
    Languages
};

/* @var $this yii\web\View */
/* @var $searchModel koperdog\yii2nsblog\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('nsblog', 'Pages');
$this->params['breadcrumbs'][] = $this->title;

koperdog\yii2nsblog\AssetBundle::register($this);
?>

<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    
    <div class="section-justify">
        <div>
            <?= Html::a(Yii::t('nsblog', 'Create Page'), ['create'], ['class' => 'btn btn-success']) ?>
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
    
    <div class="row">
    
        <div class="col-md-2 categories_gridlist">
            <?= yii\widgets\Menu::widget([
                'items' => \koperdog\yii2nsblog\services\nestedSets\MenuArray::getData(),
                'options' => ['id'=>'blog-categories-list', 'class' => 'categories_list'],
                'encodeLabels'=>false,
                'activateParents'=>true,
                'activeCssClass'=>'active',
            ]); ?>
            
            <?php //= \koperdog\yii2treeview\TreeView::widget([
//                'dataProvider' => $categoryProvider,
//                'id' => 'blog-categories-grid2',
//                'options' => ['class' => 'gridView categories_list'],
//                'summary' => false,
//                'collapse' => true,
//                'columns' => [
//                    [
//                        'label' => \Yii::t('nsblog', 'Categories'),
//                        'attribute' => 'name',
//                        'format' => 'html',
//                        'value' => function($model, $key, $index){
//                            $html  = ($model->children !== null)? '<span class="collapse_btn glyphicon glyphicon-chevron-right"></span>' : '';
//                            $class = \Yii::$app->request->get('category') == $model->id ? 'active' : ''; 
//                            $link  = array_merge(\Yii::$app->request->queryParams, ['category' => $model->id]);
//                            $html .= Html::a($model->categoryContent->name, Url::to($link), ['class' => $class]);
//                            
//                            return $html;
//                        }
//                    ],
//                ],
//            ]); ?>
        </div>
        <div class="col-md-10">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchForm,
                'id' => 'blog-grid',
                'options' => ['class' => 'gridView'],
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'options' => ['width'=>'20px'],
                    ],
                    'id',
                    [
                        'label' => 'Name',
                        'attribute' => 'name',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            return Html::a($model->pageContent->name, Url::to(['update', 'id' => $model->id]));
                        }
                    ],
                    'url:url',
                    [
                        'label' => 'Category',
                        'filter' => \yii\helpers\ArrayHelper::merge(
                                [Category::ROOT_ID => \Yii::t('nsblog', 'No Category')], // trick for filtering "no category"
                                Category::getTree(0, Domains::getEditorDomainId(), Languages::getEditorLangaugeId())
                            ),
                        'attribute' => 'category',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            return $model->category->categoryContent->name? : \Yii::t('nsblog', 'No Category');
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
                    'pageContent.image',
                    [
                        'label'     => 'status',
                        'attribute' => 'status',
                        'format'    => 'raw',
                        'filter'    => Page::getStatuses(),
                        'value' => function($model, $key, $index){
                            $html  = Html::beginTag('div', ['class' => 'switch_checkbox']);
                            $html .= Html::checkbox('status', $model->status == Page::STATUS['PUBLISHED'], ['id' => 'status_'.$model->id]);
                            $html .= Html::label('Switch', 'status_'.$model->id);
                            $html .= Html::endTag('div');
                            return $html;
                        }
                    ]
                ],
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>
