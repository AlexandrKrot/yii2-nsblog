<?php

namespace koperdog\yii2nsblog\useCases;

use \koperdog\yii2nsblog\repositories\PageRepository;
use koperdog\yii2nsblog\models\{
    Page,
    PageContent,
    forms\PageForm
};

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
    
    public function save(Page $model, \yii\base\Model $form, $domain_id = null, $language_id = null): bool
    {
        $model->load($form->attributes, '');
        $model->pageContent->load($form->pageContent->attributes, ''); 
        
        if($model->getDirtyAttributes(['category_id']) && ($model->id != $model->category_id)){
            if(empty($model->category_id)) $model->category_id = 1;
        }
        
            
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->save($model);
            $this->repository->saveContent($model->pageContent, $domain_id, $language_id);
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function create(PageForm $form)
    {
        $page = new Page();
        $page->attributes = $form->attributes;
        if(empty($page->category_id)) $page->category_id = 1;
        
        $pageContent = new PageContent();
        $pageContent->attributes = $form->pageContent->attributes;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->save($page);
            $this->repository->link('page', $page, $pageContent);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return $page;
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
