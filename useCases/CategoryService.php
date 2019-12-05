<?php

namespace koperdog\yii2nsblog\useCases;

use \koperdog\yii2nsblog\repositories\CategoryRepository;

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
}
