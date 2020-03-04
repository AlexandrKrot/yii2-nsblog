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
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return CategoryReadRepository::get($id, $domain_id, $language_id);
    }
    
    public static function getSubcategories(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return CategoryReadRepository::getSubcategories($id, $level, $domain_id, $language_id);
    }
    
    public static function getSubcategoriesAsTree(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return CategoryReadRepository::getSubcategoriesAsTree($id, $level, $domain_id, $language_id);
    }
    
    public static function getPages(int $category, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return PageReadRepository::getAllByCategory($category, $domain_id, $language_id);
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return CategoryReadRepository::getAll($domain_id, $language_id);
    }
    
    
    private static function getCurrentDomain($domain_id = null){
        return $domain_id !== null? : \Yii::$app->request->cookies->getValue('domain');
    }
    
    private static function getCurrentLanguage($language_id = null){
        return $language_id !== null? : \Yii::$app->request->cookies->getValue('language');
    }
}
