<?php

namespace koperdog\yii2nsblog\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $author_id
 * @property int $status
 * @property string $h1
 * @property string $image
 * @property string|null $preview_text
 * @property string|null $full_text
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $parent_id
 * @property int $position
 * @property int $access_read
 * @property int|null $domain_id
 * @property int|null $lang_id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $og_title
 * @property string $og_description
 * @property int $publish_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AdditionalCategory[] $additionalCategories
 * @property AdditionalCategory[] $additionalCategories0
 * @property AdditionalPage[] $additionalPages
 * @property AdditionalPage[] $additionalPages0
 * @property Domain $domain
 * @property Language $lang
 * @property MetaBlogCategory[] $metaBlogCategories
 * @property RelatedCategory[] $relatedCategories
 * @property RelatedCategory[] $relatedCategories0
 */
class Category extends \yii\db\ActiveRecord
{
    const OFFSET_ROOT = 1;
    
    public $addCategories;
    public $addPages;
    
    public $rltCategories;
    public $rltPages;
    
    public $children;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }
    
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimeStampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'author_id', 'status', 'h1', 'access_read', 'title', 'og_title'], 'required'],
            [['author_id', 'status', 'tree', 'lft', 'rgt', 'depth', 'position', 'access_read', 'domain_id', 'lang_id','parent_id'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['publish_at'], 'default', 'value' => new Expression('NOW()')],
            [['preview_text', 'full_text'], 'string'],
            [['name', 'url', 'h1', 'image', 'title', 'og_title'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
            [['addCategories', 'addPages', 'rltPages', 'rltCategories', 'description', 'keywords', 'og_description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('nsblog', 'Name'),
            'url' => Yii::t('nsblog', 'Url'),
            'author_id' => Yii::t('app', 'Author ID'),
            'status' => Yii::t('nsblog', 'Status'),
            'h1' => Yii::t('nsblog', 'H1'),
            'image' => Yii::t('nsblog', 'Image'),
            'preview_text' => Yii::t('nsblog', 'Preview Text'),
            'full_text' => Yii::t('nsblog', 'Full Text'),
            'tree' => Yii::t('nsblog', 'Tree'),
            'lft' => Yii::t('nsblog', 'Lft'),
            'rgt' => Yii::t('nsblog', 'Rgt'),
            'depth' => Yii::t('nsblog', 'Depth'),
            'parent_id' => Yii::t('nsblog', 'Category'),
            'position' => Yii::t('nsblog', 'Position'),
            'access_read' => Yii::t('nsblog', 'Access Read'),
            'domain_id' => Yii::t('nsblog', 'Domain ID'),
            'lang_id' => Yii::t('nsblog', 'Lang ID'),
            'publish_at' => Yii::t('nsblog', 'Publish At'),
            'created_at' => Yii::t('nsblog', 'Created At'),
            'updated_at' => Yii::t('nsblog', 'Updated At'),
            'addCategories' => Yii::t('nsblog', 'Additional Categories'),
            'addPages' => Yii::t('nsblog', 'Additional Pages'),
            'rltCategories' => Yii::t('nsblog', 'Related Categories'),
            'rltPages' => Yii::t('nsblog', 'Related Pages'),
        ];
    }
    
    /**
     * Get parent's ID
     * @return \yii\db\ActiveQuery 
     */
    public function getParentId()
    {
        $parent = $this->parent;
        return $parent ? $parent->id : null;
    }

    /**
     * Get parent's node
     * @return \yii\db\ActiveQuery 
     */
    public function getParent()
    {
        return $this->parents(1)->one();
    }

    /**
     * Get a full tree as a list, except the node and its children
     * @param  integer $node_id node's ID
     * @return array array of node
     */
    public static function getTree($node_id = 0)
    {
        // don't include children and the node
        $children = [];

        if ( ! empty($node_id))
            $children = array_merge(
                self::findOne($node_id)->children()->column(),
                [$node_id]
                );

        $rows = self::find()->
            select('id, name, depth')->
            andWhere(['!=', 'id', 1])->    
            andWhere(['NOT IN', 'id', $children])->
            orderBy('tree, lft, position')->
            all();

        $return = [];
        foreach ($rows as $row)
            $return[$row->id] = str_repeat('-', $row->depth - self::OFFSET_ROOT) . ' ' . $row->name;

        return $return;
    }
    
    /**
     * Get a full tree as a list, except the node and its children
     * @param  integer $node_id node's ID
     * @return array array of node
     */
    public static function getTreeArray($node_id = 0)
    {
        $children = [];

        if ( ! empty($node_id)){
            $children = array_merge(self::findOne($node_id)->children()->column(),[$node_id]);
        }

        $rows = self::find()
            ->where(['NOT IN', 'id', $children])
            ->orderBy('tree, lft, position')
            ->asArray()
            ->all();

        $return  = [];
        $last_id = null;
        $level   = 0;
        
        foreach ($rows as $row){
            if($last_id && $row['depth'] > $level){
                $return[$last_id]['child'] = $row;
            }
            else{
                $return[$row['id']] = $row;
            }
            $level   = $row['depth'];
            $last_id = $row['id'];
        }

        return $return;
    }
    
    public function afterFind() {
        parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes) { 
            $this->saveAdditionalCategories();
            $this->saveAdditionalPages();
            $this->saveRelatedPages();
            $this->saveRelatedCategories();
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    private function saveAdditionalPages()
    {
        $this->addPages = is_array($this->addPages)? $this->addPages : [];
        
        $current = \yii\helpers\ArrayHelper::getColumn($this->additionalPages, 'id');
                
        foreach(array_filter(array_diff($this->addPages, $current)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->link('additionalPages', $page, ['type' => 0, 'source_type' => 0]);
        }
        
        foreach(array_filter(array_diff($current, $this->addPages)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->unlink('additionalPages', $page, true);
        }
        
    }
    
    private function saveAdditionalCategories()
    {
        $this->addCategories = is_array($this->addCategories)? $this->addCategories : [];
        
        $current = \yii\helpers\ArrayHelper::getColumn($this->additionalCategories, 'id');
        
        foreach(array_filter(array_diff($this->addCategories, $current)) as $catId){
            $category = self::findOne($catId);
            
            $this->link('additionalCategories', $category, ['type' => 0, 'source_type' => 0]);
        }
        
        foreach(array_filter(array_diff($current, $this->addCategories)) as $catId){
            $category = self::findOne($catId);
            
            $this->unlink('additionalCategories', $category, true);
        }
    }
    
    private function saveRelatedPages()
    {
        $this->rltPages = is_array($this->rltPages)? $this->rltPages : [];
        $current = \yii\helpers\ArrayHelper::getColumn($this->relatedPages, 'id');
        
        foreach(array_filter(array_diff($this->rltPages, $current)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->link('relatedPages', $page, ['type' => 1, 'source_type' => 0]);
        }

        
        foreach(array_filter(array_diff($current, $this->rltPages)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->unlink('relatedPages', $page, true);
        }
    }
    
    private function saveRelatedCategories()
    {
        $this->rltCategories = is_array($this->rltCategories)? $this->rltCategories : [];
        $current = \yii\helpers\ArrayHelper::getColumn($this->relatedCategories, 'id');
        
        foreach(array_filter(array_diff($this->rltCategories, $current)) as $catId){
            $category = self::findOne($catId);
            
            $this->link('relatedCategories', $category, ['type' => 1, 'source_type' => 0]);
        }
        
        foreach(array_filter(array_diff($current, $this->rltCategories)) as $catId){
            $category = self::findOne($catId);
            
            $this->unlink('relatedCategories', $category, true);
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalCategories()
    {
        return $this->hasMany(self::className(), ['id' => 'category_id'])
                ->viaTable(CategoryAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 0, 'source_type' => 0]);
                });
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedCategories()
    {
        return $this->hasMany(self::className(), ['id' => 'category_id'])
                ->viaTable(CategoryAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 1, 'source_type' => 0]);
                });
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])
                ->viaTable(PageAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 0, 'source_type' => 0]);
                });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])
                ->viaTable(PageAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 1, 'source_type' => 0]);
                });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Language::className(), ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaBlogCategories()
    {
        return $this->hasOne(MetaBlogCategory::className(), ['src_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\Yii::$app->user->identityClass, ['id' => 'author_id']);
    }
}
