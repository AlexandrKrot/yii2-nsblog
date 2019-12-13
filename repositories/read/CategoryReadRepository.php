<?php

namespace koperdog\yii2nsblog\repositories\read;
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
class CategoryReadRepository {
    
    public function get(int $id): ?Category
    {
        return Category::find()->where(['id' => $id])->asArray()->one();
    }
    
    public function getByUrl(array $sections): ?Category
    {
        
    }
}
