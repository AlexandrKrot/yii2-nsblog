<?php

namespace koperdog\yii2nsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
use yii\base\InvalidParamException;
use koperdog\yii2nsblog\repositories\read\CategoryReadRepository;
use koperdog\yii2nsblog\models\Category;
use yii\helpers\ArrayHelper;

class CategoryUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private $repository;
    
    public function __construct(CategoryReadRepository $repository, $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
    }
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/category') {
            if (empty($params['id'])) throw new InvalidParamException('Empty id.');
            $id = $params['id'];
            
            $url = \Yii::$app->cache->getOrSet(['category_route', 'id' => $id], function() use ($id) {
                if (!$category = $this->repository->getCategory($id)) {
                    return null;
                }
                return $this->getCategoryPath($category);
            });
            
            if (!$url) {
                throw new InvalidParamException('Undefined id.');
            }
            
            $url = $this->prefix . '/' . $url;
            
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }
            return $url;
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/(.*[a-z])$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            $result = \Yii::$app->cache->getOrSet(['category_route', 'path' => $path], function () use ($path) {
                if (!$category = $this->repository->getByUrl($this->getPathUrl($path))) {
                    return ['id' => null, 'path' => null];
                }
                return ['id' => $category->id, 'path' => $this->getCategoryPath($category)];
            }, null, new TagDependency(['tags' => ['categories']]));
            
            if (empty($result['id'])) {
                return false;
            }
            
            if ($path != $result['path']) {
                throw new UrlNormalizerRedirectException(['shop/catalog/category', 'id' => $result['id']], 301);
            }
            return ['blog/category', ['id' => $result['id']]];
        }
        return false;  // данное правило не применимо
    }
    
    private function getCategoryPath(Category $category): string
    {
        $sections = ArrayHelper::getColumn($category->getParents()->andWhere(['>', 'depth', Category::OFFSET_ROOT])->all(), 'url');
        $sections[] = $category->url;
        return implode('/', $sections);
    }
    
    private function getPathUrl(string $url): string
    {
        $sections = explode('/', $url);
        return array_filter($sections);
    }
}