<?php

namespace koperdog\yii2nsblog\models\dto;

class CategoryDto extends BaseDto
{    
    public $children;
    
    public $id;
    public $url;
    public $status;
    public $author_id;
    public $parent_id;
    public $publish_at;
    public $created_at;
    public $updated_at;
    
    public $addCategories;
    public $addPages;
    public $rltCategories;
    public $rltPages;
    public $access_read;
    
    public $name;
    public $category_id;
    public $h1;
    public $image;
    public $preview_text;
    public $full_text;
    
    public $language_id;
    public $domain_id;

    public $title;
    public $keywords;
    public $description;
    public $og_title;
    public $og_description;
    
    public $lft;
    public $rgt;
    public $depth;
    public $tree;
}
