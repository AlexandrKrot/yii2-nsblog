<?php

namespace koperdog\yii2nsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
use yii\base\InvalidParamException;
use koperdog\yii2nsblog\repositories\CategoryRepository;
use koperdog\yii2nsblog\models\Category;
use yii\helpers\ArrayHelper;

class CategoryUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private $repository;
    
    public function init()
    {
        parent::init();
        
        debug('da');
    }
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/category') {
            if (empty($params['id'])) throw new InvalidParamException('Empty id.');
            $id = $params['id'];
            
            $url = \Yii::$app->cache->getOrSet(['category_route', 'id' => $id], function() use ($id) {
                if (!$category = $this->repository->get($id)) {
                    return null;
                }
                return $this->getCategoryPath($category);
            });
            
            if (!$url) {
                throw new InvalidParamException('Undefined id.');
            }
            
            $url = $this->prefix . '/' . $url;
            
            unset($params['id']);
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }
            return $url;
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/?(.*[a-z])/?$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            \Yii::$app->cache->flush();
            $result = \Yii::$app->cache->getOrSet(['category_route', 'path' => $path], function () use ($path) {
                
                if (!$category = $this->repository->getByPath($path)) {
                    return ['id' => null, 'path' => null];
                }
                
                return ['id' => $category->id];
            });
            
            if (empty($result['id'])) {
                return false;
            }
            
            return ['blog/categories/view', ['id' => $result['id']]];
        }
        
        return false;
    }
    
    private function getCategoryPath(Category $category): string
    {
        $sections = ArrayHelper::getColumn($category->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all(), 'url');
        $sections[] = $category->url;
        return implode('/', $sections);
    }
}