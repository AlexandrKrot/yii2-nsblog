<?php

namespace koperdog\yii2nsblog\repositories;
use koperdog\yii2nsblog\models\{
    CategorySearch,
    Category    
};

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryRepository {
    
    private $searchModel = null;
    
    public function getSearchModel(): ?CategorySearch
    {
        return $this->searchModel;
    }
    
    public function get(int $id): Category
    {
        if(!$model = Category::findOne($id)){
            throw new \DomainException("Category with id: {$id} was not found");
        }
        
        return $model;
    }
    
    public function getParentNodeById(int $id): Category
    {
        if(!$model = Category::findOne($id)->parents(1)->one()){
            throw new \DomainException("Category have not parents");
        }
        
        return $model;
    }
    
    public function search(array $params = []): \yii\data\BaseDataProvider
    {
        $this->searchModel = new CategorySearch();
        $dataProvider = $this->searchModel->search($params);
        
        return $dataProvider;
    }
    
    public function save(Category $model): bool
    {
        if(!$model->save()){
            throw new \RuntimeException('Error saving model');
        }
        
        return true;
    }
    
    public function setPosition(Category $model, Category $parentNode): bool
    {
        if(!$model->appendTo($parentNode)){
            throw new \RuntimeException('Error saving model');
        }
        
        return true;
    }
}
