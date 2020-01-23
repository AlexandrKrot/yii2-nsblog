<?php

namespace koperdog\yii2nsblog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use koperdog\yii2nsblog\models\CategoryValue;

/**
 * CategorySearch represents the model behind the search form of `koperdog\yii2nsblog\models\Category`.
 */
class CategorySearch extends CategoryValue
{
    public $url;
    public $status;
    public $author_id;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'author', 'name', 'h1', 'image', 'preview_text', 'full_text'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {   
        $this->load($params);
        
        $query = Category::find()
            ->joinWith(['categoryValue' => function($query){
                return CategoryValueQuery::getAll()
                        ->andFilterWhere(['like', 'name', $this->name]);
            }])
            ->andWhere(['!=', 'category.id', 1]);
                
                
        $query->andFilterWhere(['like', 'category_value.name', $this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'url', $this->url]);
        
        $query->with('author');
       
        $query->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC]);
        

        return $dataProvider;
    }
}
