<?php

namespace koperdog\yii2nsblog\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $author_id
 * @property int $category_id
 * @property string $image
 * @property string|null $preview_text
 * @property string|null $full_text
 * @property int $position
 * @property int|null $domain_id
 * @property int|null $lang_id
 * @property int $publish_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AdditionalPage[] $additionalPages
 * @property AdditionalPage[] $additionalPages0
 * @property MetaBlogPage[] $metaBlogPages
 * @property Domain $domain
 * @property Language $lang
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'image', 'publish_at',], 'required'],
            [['author_id', 'position', 'domain_id', 'lang_id', 'publish_at', 'created_at', 'updated_at', 'category_id'], 'integer'],
            [['preview_text', 'full_text'], 'string'],
            [['name', 'url', 'image'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => \koperdog\yii2sitemanager\models\Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
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
            'image' => Yii::t('app', 'Image'),
            'preview_text' => Yii::t('app', 'Preview Text'),
            'full_text' => Yii::t('app', 'Full Text'),
            'position' => Yii::t('app', 'Position'),
            'domain_id' => Yii::t('app', 'Domain ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'publish_at' => Yii::t('app', 'Publish At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'addPages'   => Yii::t('app', 'Additional Pages'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages()
    {
        return $this->hasMany(AdditionalPage::className(), ['child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages0()
    {
        return $this->hasMany(AdditionalPage::className(), ['child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaBlogPages()
    {
        return $this->hasMany(MetaBlogPage::className(), ['src_id' => 'id']);
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
}
