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
    
    public static function get(int $id, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = $domain_id !== null? : null; // !!!! Вставить $domain_id из cookie/session
        $language_id = $language_id !== null? : null; // !!!! Вставить $language_id из cookie/session
        
        return CategoryReadRepository::get($id, $domain_id, $language_id);
    }
    
    public static function getSubcategories(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = $domain_id !== null? : null; // !!!! Вставить $domain_id из cookie/session
        $language_id = $language_id !== null? : null; // !!!! Вставить $language_id из cookie/session
        
        return CategoryReadRepository::getSubcategories($id, $level, $domain_id, $language_id);
    }
    
    public static function getSubcategoriesAsTree(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = $domain_id !== null? : null; // !!!! Вставить $domain_id из cookie/session
        $language_id = $language_id !== null? : null; // !!!! Вставить $language_id из cookie/session
        
        return CategoryReadRepository::getSubcategoriesAsTree($id, $level, $domain_id, $language_id);
    }
    
    public static function getPages(int $category): ?array
    {
        $domain_id   = $domain_id !== null? : null; // !!!! Вставить $domain_id из cookie/session
        $language_id = $language_id !== null? : null; // !!!! Вставить $language_id из cookie/session
        
        return PageReadRepository::getPages($category);
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        $domain_id   = $domain_id !== null? : null; // !!!! Вставить $domain_id из cookie/session
        $language_id = $language_id !== null? : null; // !!!! Вставить $language_id из cookie/session
        
        return CategoryReadRepository::getAll($domain_id, $language_id);
    }
    
}
