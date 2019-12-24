<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\components\url;

use koperdog\yii2nsblog\repositories\PageRepository;
use yii\helpers\ArrayHelper;
use koperdog\yii2nsblog\models\Category;

/**
 * Description of CategoryUrl
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageUrl extends Url {
    
    protected $routeName = 'page_route';
    
    protected $urlPath   = 'blog/pages/view';
    
    public function __construct(PageRepository $repository, $config = [])
    {
        parent::__construct($config);
        
        $this->repository = $repository;
    }
    
    protected function getPath($category): string
    {
        $sections = ArrayHelper::getColumn($category->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all(), 'url');
        $sections[] = $category->url;
        return implode('/', $sections);
    }
}
