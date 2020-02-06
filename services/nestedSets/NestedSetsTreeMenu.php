<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\services\nestedSets;

use Yii;
use koperdog\yii2nsblog\models\Category;

/**
 * Description of NestedSetsTreeMenu
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class NestedSetsTreeMenu extends NestedSetsTree
{

    /**
     * @var string
     */
    public $childrenOutAttribute = 'items'; //children

    /**
     * @var string
     */
    public $labelOutAttribute = 'label'; //title


    /**
     * Добавляет в массив дополнительные элементы
     * @param $node
     * @return array
     */
    public function addItem($node)
    {
        $node = $this->renameTitle($node); //переименование элемента массива
        $this->changeUrl($node);
        $node = $this->visible($node); //видимость элементов меню
        $node = $this->makeActive($node); //выделение активного пункта меню

        return $node;
    }
    
    protected function changeUrl(&$node){
        $node['url'] = array_merge(\Yii::$app->request->queryParams, ['category' => $node['id']]);
    }


    /**
     * Переименовываем элемент "name" в "label" (создаем label, удаляем name)
     * требуется для yii\widgets\Menu
     * @param $node
     * @return array
     */
    protected function renameTitle($node)
    {
        $prefix = '';
        
        if($node[$this->childrenOutAttribute] !== null){
            $prefix = '<span class="collapse_btn glyphicon glyphicon-chevron-right"></span>';
        }
        
        $prefix .= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $node[$this->depthAttribute] - Category::OFFSET_ROOT);
        $newNode = [
            $this->labelOutAttribute => $prefix.$node['categoryContent'][$this->labelAttribute],
        ];
        unset($node[$this->labelAttribute]);

        return array_merge($node, $newNode);
    }


    /**
     * Видимость пункта меню (visible = false - скрыть элемент)
     * @param $node
     * @return array
     */
    protected function visible($node)
    {
        $newNode = [];

        //Гость
        if (Yii::$app->user->isGuest) {

            //Действие logout по-умолчанию проверяется на метод POST.
            //При использовании подкорректировать VerbFilter в контроллере (удалить это действие или добавить GET).
            if ($node['url'] === '/logout') {
                $newNode = [
                    'visible' => false,
                ];
            }

        //Авторизованный пользователь
        } else {
            if ($node['url'] === '/login' || $node['url'] === '/signup') {
                $newNode = [
                    'visible' => false,
                ];
            }
        }

        return array_merge($node, $newNode);
    }



    /**
     * Добавляет элемент "active" в массив с url соответствующим текущему запросу
     * для назначения отдельного класса активному пункту меню
     *
     * @param $node
     * @return array
     */
    private function makeActive($node)
    {
        $path = Yii::$app->request->get();        
        $node['active'] = ($path['category'] == $node['id'] || $path['PageSearch']['category'] == $node['id']);
        return $node;
    }

}