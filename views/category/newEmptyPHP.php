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
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('nsblog', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    
    
    <div id="grid" class="grid-view">
        <div class="summary">Showing <b>1-4</b> of <b>4</b> items.</div>
            <div class="table table-striped table-bordered">
                <div class="thead">
                    <div class="tr">
                        <div><input type="checkbox" class="select-on-check-all" name="selection_all" value="1"></div>
                        <div><a href="/admin/blog/category/index?sort=name" data-sort="name">Name</a></div>
                        <div><a href="/admin/blog/category/index?sort=url" data-sort="url">Url</a></div>
                        <div><a href="/admin/blog/category/index?sort=author_id" data-sort="author_id">Author ID</a></div>
                        <div><a href="/admin/blog/category/index?sort=status" data-sort="status">Status</a></div>
                        <div class="action-column">&nbsp;</div>
                    </div>
                </div>
                <div class="tbody">
                
    <?php 
        $level=-1;

        foreach($dataProvider->getModels() as $n => $category)
        {
            if($category->depth==$level)
                echo Html::endTag('div');
            else if($category->depth>$level)
                echo Html::beginTag('div', ['class' => 'subcategories']);
            else
            {
                echo Html::endTag('div');

                for($i=$level-$category->depth;$i;$i--)
                {
                    echo Html::endTag('div');
                    echo Html::endTag('div');
                }
            }

            echo Html::beginTag('div', ['class' => 'category', 'data-id' => $category->id]);
            echo Html::encode($category->name);
            $level=$category->depth;
        }

        for($i=$level;$i;$i--)
        {
            echo Html::endTag('div');
            echo Html::endTag('div');
        }
    ?>
                </div>
            </div>
    </div>
    
    

    <?php Pjax::end(); ?>

</div>