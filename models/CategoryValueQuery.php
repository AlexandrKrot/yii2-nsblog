<?php

namespace koperdog\yii2nsblog\models;

use koperdog\yii2nsblog\models\CategoryValue;
use koperdog\yii2sitemanager\repositories\DomainRepository;

/**
 * This is the ActiveQuery class for [[CategoryValue]].
 *
 * @see CategoryValue
 */
class CategoryValueQuery extends \yii\db\ActiveQuery
{
    public static function get(int $id, $domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryValue::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryValue::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryValue::tableName())
                    ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryValue::find()
                ->andWhere(['category_id' => $id, 'domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'setting_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
    
    public static function getAll($domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryValue::find()
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryValue::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryValue::tableName())
                    ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryValue::find()
                ->andWhere(['domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'setting_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
}
