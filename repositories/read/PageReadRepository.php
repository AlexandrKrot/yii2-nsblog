<?php

namespace koperdog\yii2nsblog\repositories\read;
use koperdog\yii2nsblog\models\{
    Page    
};

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageReadRepository {
    
    public static function get(int $id): ?array
    {
        return Page::find()->where(['id' => $id])->asArray()->one();
    }
    
    public static function getPages(int $category): ?array
    {
        return Page::find()->where(['category_id' => $category])->asArray()->all();
    }
    
    public static function getAll(): ?array
    {
        return Page::find()->asArray()->all();
    }
}
