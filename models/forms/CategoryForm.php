<?php

namespace koperdog\yii2nsblog\models\forms;

use yii\base\Model;
use koperdog\yii2nsblog\models\{
    Category,
    Page
};

/**
 * Setting create form
 */
class CategoryForm extends Model
{    
    public $id;
    public $url;
    public $status;
    public $author_id;
    public $parent_id;
    public $publish_at;
    
    public $name;
    public $category_id;
    public $h1;
    public $image;
    public $preview_text;
    public $full_text;
    public $addCategories;
    public $addPages;
    public $rltCategories;
    public $rltPages;
    public $access_read;
    
    public $language_id;
    public $domain_id;

    public $title;
    public $keywords;
    public $description;
    public $og_title;
    public $og_description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'h1', 'image', 'preview_text', 'full_text', 'title', 'og_title', 'keywords', 'description', 'og_description'], 'required'],
            [['category_id', 'language_id'], 'integer'],
            [['preview_text', 'full_text', 'description', 'og_description'], 'string'],
            [['name', 'h1', 'image', 'title', 'og_title', 'keywords'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            
            [['url', 'status', 'publish_at', 'access_read'], 'required'],
            [['author_id', 'status', 'access_read', 'parent_id'], 'integer'],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['publish_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['url'], 'string', 'max' => 255],
            ['url', 'checkUrl'],
            [['author_id'], 'default', 'value' => \Yii::$app->user->id],
            [['addCategories', 'addPages', 'rltPages', 'rltCategories'], 'safe'],
        ];
    }
    
    public function checkUrl($attribute, $params)
    {       
        if(
            Category::find()->where(['url' => $this->url, 'parent_id' => $this->parent_id])->andWhere(['!=', 'id', $this->id])->exists() || 
            Page::find()->where(['category_id' => $this->parent_id, 'url' => $this->url])->exists()
        ){
            $this->addError($attribute, \Yii::t('nsblog/error', 'This Url already exists'));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'category_id' => \Yii::t('app', 'Category ID'),
            'name' => \Yii::t('app', 'Name'),
            'h1' => \Yii::t('app', 'H1'),
            'image' => \Yii::t('app', 'Image'),
            'preview_text' => \Yii::t('app', 'Preview Text'),
            'full_text' => \Yii::t('app', 'Full Text'),
            'title' => \Yii::t('app', 'Title'),
            'og_title' => \Yii::t('app', 'Og Title'),
            'keywords' => \Yii::t('app', 'Keywords'),
            'description' => \Yii::t('app', 'Description'),
            'og_description' => \Yii::t('app', 'Og Description'),
        ];
    }
}
