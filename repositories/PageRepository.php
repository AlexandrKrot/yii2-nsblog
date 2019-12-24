<?php

namespace koperdog\yii2nsblog\repositories;
use koperdog\yii2nsblog\models\{
    Page,
    PageSearch
};

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageRepository {
    
    private $searchModel = null;
    
    public function getSearchModel(): ?PageSearch
    {
        return $this->searchModel;
    }
    
    public function get(int $id): Page
    {
        if(!$model = Page::findOne($id)){
            throw new \DomainException("Page with id: {$id} was not found");
        }
        
        return $model;
    }
    
    public function search(array $params = []): \yii\data\BaseDataProvider
    {
        $this->searchModel = new PageSearch();
        $dataProvider = $this->searchModel->search($params);
        
        return $dataProvider;
    }
    
    public function save(Page $model): bool
    {
        if(!$model->save()){
            throw new \RuntimeException('Error saving model');
        }
        
        return true;
    }
    
    public function delete(Post $model):void
    {
        $model->delete();
    }
    
    public static function getAll($exclude = null): ?array
    {
        return Page::find()
                ->select(['id', 'name'])
                ->andWhere(['NOT IN', 'id', 1])
                ->andFilterWhere(['NOT IN', 'id', $exclude])
                ->all();
    }
}
