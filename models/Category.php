<?php

namespace koperdog\yii2nsblog\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

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
 * @property int $position
 * @property int $access_read
 * @property int|null $domain_id
 * @property int|null $lang_id
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
            \yii\behaviors\TimeStampBehavior::className(),
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                //'treeAttribute' => 'tree',
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
            [['name', 'url', 'author_id', 'status', 'h1', 'access_read'], 'required'],
            [['author_id', 'status', 'tree', 'lft', 'rgt', 'depth', 'position', 'access_read', 'domain_id', 'lang_id', 'publish_at', 'created_at', 'updated_at'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['preview_text', 'full_text'], 'string'],
            [['name', 'url', 'h1', 'image'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Url'),
            'author_id' => Yii::t('app', 'Author ID'),
            'status' => Yii::t('app', 'Status'),
            'h1' => Yii::t('app', 'H1'),
            'image' => Yii::t('app', 'Image'),
            'preview_text' => Yii::t('app', 'Preview Text'),
            'full_text' => Yii::t('app', 'Full Text'),
            'tree' => Yii::t('app', 'Tree'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'position' => Yii::t('app', 'Position'),
            'access_read' => Yii::t('app', 'Access Read'),
            'domain_id' => Yii::t('app', 'Domain ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'publish_at' => Yii::t('app', 'Publish At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalCategories()
    {
        return $this->hasMany(AdditionalCategory::className(), ['child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalCategories0()
    {
        return $this->hasMany(AdditionalCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages()
    {
        return $this->hasMany(AdditionalPage::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages0()
    {
        return $this->hasMany(AdditionalPage::className(), ['parent_id' => 'id']);
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
        return $this->hasMany(MetaBlogCategory::className(), ['src_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedCategories()
    {
        return $this->hasMany(RelatedCategory::className(), ['child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\Yii::$app->user->identityClass, ['id' => 'author_id']);
    }
}
