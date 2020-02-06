<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\services\nestedSets;

use koperdog\yii2nsblog\models\Category;
use koperdog\yii2sitemanager\components\{
    Domains,
    Languages,
};

/**
 * Description of MenuArray
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class MenuArray {
    static function getData()
    {

        $collection = \koperdog\yii2nsblog\helpers\CategoryHelper::getAll(Domains::getEditorDomainId(), Languages::getEditorLangaugeId());
        
        $menu = [];

        if($collection){
            $nsTree = new NestedSetsTreeMenu();
            $menu = $nsTree->tree($collection);
        }
        
        $path = \Yii::$app->request->get();
        $default = [
            'url' => array_merge(\Yii::$app->request->queryParams, ['category' => Category::ROOT_ID]),
            'label' => \Yii::t('nsblog', 'No Category'),
            'active' => ($path['category'] == Category::ROOT_ID || $path['PageSearch']['category'] == Category::ROOT_ID)
        ];
        
        array_unshift($menu, $default);

        return $menu;
    }
}
