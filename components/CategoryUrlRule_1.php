<?php

namespace koperdog\yii2nsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class CategoryUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private $category;
    
    private $repository;
        
    public function __construct(\koperdog\yii2nsblog\repositories\CategoryRepository $repos,$config = [])
    {
        parent::__construct($config);
        $this->repository = $repos;
//        $this->initManagers();
    }
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/category') {
//            return $this->category->createUrl($params);
        }
        
        return false;
    }

    public function parseRequest($manager, $request)
    {
        
        
        
        if (preg_match('#^' . $this->prefix . '/?(.*[a-z])/?$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            
//            if($result = $this->category->parseRequest($path)){
//                return $result;
//            }
            return false;
        }
        
        return false;
    }
    
    private function initManagers()
    {
        $this->category = \Yii::createObject(['class' => url\CategoryUrl::className(), 'owner' => $this]);
    }
}