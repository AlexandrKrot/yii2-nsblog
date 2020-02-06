<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\helpers;

use koperdog\yii2nsblog\interfaces\BlogHelper;
use koperdog\yii2nsblog\repositories\read\{
    CategoryReadRepository,
    PageReadRepository
};
use koperdog\yii2nsblog\models\Category;

/**
 * Description of CategoryHelper
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class CategoryHelper implements BlogHelper{
    
    public static function get(int $id): ?array
    {
        return CategoryReadRepository::get($id);
    }
    
    public static function getSubcategories(int $id, int $level = 1): ?array
    {
        return CategoryReadRepository::getSubcategories($id, $level);
    }
    
    public static function getSubcategoriesAsTree(int $id, int $level = 1): ?array
    {
        return CategoryReadRepository::getSubcategoriesAsTree($id, $level);
    }
    
    public static function getPages(int $category): ?array
    {
        return PageReadRepository::getPages($category);
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        return CategoryReadRepository::getAll($domain_id, $language_id);
    }
    
}
