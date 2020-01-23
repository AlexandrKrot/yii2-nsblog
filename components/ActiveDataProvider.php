<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\components;

use \koperdog\yii2nsblog\models\dto\CategoryDto;

/**
 * Description of ActiveDataProvider
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class ActiveDataProvider extends \yii\data\ActiveDataProvider{
    public function getModels() {
        $models = parent::getModels();
        
        foreach($models as &$model){
            $tmp = new CategoryDto();
            $tmp->import($model->attributes);
            $tmp->import($model->category->attributes);
            $model = $tmp;
        }
        
        return $models;
    }
}