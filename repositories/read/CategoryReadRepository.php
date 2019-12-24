<?php

namespace koperdog\yii2nsblog\repositories\read;
use koperdog\yii2nsblog\models\{
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
    
    public static function getByPath(string $path): ?Category
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
}
