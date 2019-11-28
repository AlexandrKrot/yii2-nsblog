<?php

namespace koperdog\yii2nsblog\models;

use Yii;

/**
 * This is the model class for table "{{%meta_blog_category}}".
 *
 * @property int $id
 * @property int $src_id
 * @property string|null $title
 * @property string|null $keywords
 * @property string|null $description
 * @property string|null $og:title
 * @property string|null $og:description
 * @property int|null $domain_id
 * @property int|null $lang_id
 *
 * @property Domain $domain
 * @property Language $lang
 * @property Category $src
 */
class MetaBlogCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%meta_blog_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['src_id'], 'required'],
            [['src_id', 'domain_id', 'lang_id'], 'integer'],
            [['description', 'og:description'], 'string'],
            [['title', 'keywords', 'og:title'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
            [['src_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['src_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'src_id' => Yii::t('app', 'Src ID'),
            'title' => Yii::t('app', 'Title'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
            'og:title' => Yii::t('app', 'Og:title'),
            'og:description' => Yii::t('app', 'Og:description'),
            'domain_id' => Yii::t('app', 'Domain ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
        ];
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
    public function getSrc()
    {
        return $this->hasOne(Category::className(), ['id' => 'src_id']);
    }
}
