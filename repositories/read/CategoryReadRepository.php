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
    
    public static function get(int $id): ?array
    {
        return Category::find()->where(['id' => $id])->asArray()->one();
    }
    
    public static function getSubcategories(int $id, int $level = 1): ?array
    {
        $category = Category::findOne($id);
        
        if($category){
            return $category->children($level)->asArray()->all();
        }
        
        return null;
    }
    
    public static function getSubcategoriesAsTree(int $id, int $level = 1): ?array
    {
        return self::asTree(self::getSubcategories($id, $level));
    }
    
    public static function getAll(): ?array
    {
        return Category::find()->asArray()->all();
    }
        
    private static function asTree(array $models): ?array
    {
        $tree = [];

        foreach ($models as $n) {
            $node = &$tree;

            for ($depth = $models[0]->depth; $n->depth > $depth; $depth++) {
                $node = &$node[count($node) - 1]->children;
            }
            $n->children = null;
            $node[] = $n;
        }
        return $tree;
    }
}
