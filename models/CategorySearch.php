<?php

namespace koperdog\yii2nsblog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use koperdog\yii2nsblog\models\Category;

/**
 * CategorySearch represents the model behind the search form of `koperdog\yii2nsblog\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'status', 'tree', 'lft', 'rgt', 'depth', 'position', 'access_read', 'domain_id', 'lang_id', 'publish_at', 'created_at', 'updated_at'], 'integer'],
            [['name', 'url', 'h1', 'image', 'preview_text', 'full_text'], 'safe'],
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
        $query = Category::find();
        $query->andWhere(['!=', 'id', 1]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'status' => $this->status,
            'tree' => $this->tree,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'depth' => $this->depth,
            'position' => $this->position,
            'access_read' => $this->access_read,
            'domain_id' => $this->domain_id,
            'lang_id' => $this->lang_id,
            'publish_at' => $this->publish_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'preview_text', $this->preview_text])
            ->andFilterWhere(['like', 'full_text', $this->full_text]);
        
        $query->with('author');
       
        $query->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC]);
        

        return $dataProvider;
    }
}
