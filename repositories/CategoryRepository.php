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
    
    public function getParents(Category $model): ?array
    {
        $parents = $model->parents()->all(); // offset depth shift 
        array_shift($parents);
        return $parents;
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
    
    public function appendTo(Category $model): bool
    {
        $parent = $this->get($model->parent_id);
        if(!$model->appendTo($parent)){
            throw new \RuntimeException("Error append model");
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
    
    public function getByPath(string $path): ?Category
    {
        $sections = explode('/', $path);
        
        $category = Category::find()
                ->where(['url' => array_shift($sections), 'depth' => Category::OFFSET_ROOT])
                ->one();
        
        $offset = Category::OFFSET_ROOT + 1; // +1 because array shift from sections
        
        foreach($sections as $key => $section){
            if($category){
                $category = $category->children(1)->where(['url' => $section, 'depth' => $key + $offset])->one();
            }
        }
        
        return $category;
    }
    
    public function delete(Categroy $model): bool
    {
        if ($model->isRoot()){
            $model->deleteWithChildren();
        }
        else{
            $model->delete();
        }
    }
    
    public static function getAll($exclude = null): ?array
    {
        return Category::find()
                ->select(['id', 'name'])
                ->andWhere(['NOT IN', 'id', 1])
                ->andFilterWhere(['NOT IN', 'id', $exclude])
                ->all();
    }
}
