<?php

namespace koperdog\yii2nsblog\useCases;

use \koperdog\yii2nsblog\repositories\PageRepository;
use koperdog\yii2nsblog\models\Page;

/**
 * Description of CategoryService
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageService {
    
    private $repository;
    
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function get(int $id): ?Page
    {
        $model = Page::find()
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
    
    public function save(Page $model): bool
    {
        if($model->getDirtyAttributes(['parent_id']) && ($model->id != $model->parent_id)){
            if(empty($model->parent_id)) $model->parent_id = 1;
        }
            
//        $transaction = \Yii::$app->db->beginTransaction();
//        try{
            $this->repository->save($model);
//        } catch(\Exception $e){
//            $transaction->rollBack();
//            return false;
//        }
        
        return true;
    }
    
    public function create(Page $form): bool
    {
        $page = new Page();
        $page->attributes = $form->attributes;
        if(empty($page->parent_id)) $page->parent_id = 1;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->save($page);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function delete(Page $model): bool
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
