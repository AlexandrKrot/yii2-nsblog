<?php

namespace koperdog\yii2nsblog\useCases;

use \koperdog\yii2nsblog\repositories\CategoryRepository;
use \koperdog\yii2nsblog\models\{
    forms\CategoryForm,
    Category,
    CategoryValue
};

/**
 * Description of CategoryService
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryService {
    
    private $repository;
    
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function get(int $id): ?Category
    {
        $model = Category::find()
                ->with('additionalPages')
                ->with('additionalCategories')
                ->where(['id' => $id])
                ->one();
        
        $model->addCategories = $model->additionalCategories;
        $model->addPages      = $model->additionalPages;
        
        $model->rltCategories = $model->relatedCategories;
        $model->rltPages      = $model->relatedPages;
        
        return $model;
    }
    
    public function save(Category $model): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            if($model->getDirtyAttributes(['parent_id']) && ($model->id != $model->parent_id)){
                if(empty($model->parent_id)) $model->parent_id = 1;
                $this->repository->appendTo($model);
            }
            else{
                $this->repository->save($model);
            }
            
            $this->repository->save($model->categoryValue);
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function create(CategoryForm $form): ?Category
    {
        $category = new Category();
        $category->attributes = $form->attributes;
        if(empty($category->parent_id)) $category->parent_id = 1;
        
        $categoryValue = new CategoryValue();
        $categoryValue->attributes = $form->attributes;
        
        $categoryValue->domain_id   = null;
        $categoryValue->language_id = null;
                
//        $transaction = \Yii::$app->db->beginTransaction();
//        try{
            $this->repository->appendTo($category);
            $this->repository->link('category', $category, $categoryValue);
//            $transaction->commit();
//        } catch(\Exception $e){
//            $transaction->rollBack();
//            return null;
//        }
        
        return $category;
    }
    
    public function sort(array $data): int
    {
        $result = 0;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $parentNode = $this->repository->getParentNodeById($data[0]);
            
            foreach($data as $index => $value){
                $category = $this->repository->get($value);
                $category->position = (int)$index;
                                
                $this->repository->setPosition($category, $parentNode);
                $result++;
            }
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return 0;
        }
        
        return $result;
    }
    
    public function delete(Category $model): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->delete($model);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
}
