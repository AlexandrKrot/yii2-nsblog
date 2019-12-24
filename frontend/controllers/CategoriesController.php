<?php

namespace koperdog\yii2nsblog\frontend\controllers;

use Yii;
use koperdog\yii2nsblog\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoriesController extends Controller
{
    
    private $categoryService;
    private $categoryRepository;
    
    public function __construct
    (
        $id, 
        $module, 
        \koperdog\yii2nsblog\useCases\CategoryService $categoryService,
        \koperdog\yii2nsblog\repositories\CategoryRepository $categoryRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        
        $this->categoryService    = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function actionView($id)
    {
        $model   = $this->findModel($id);
        
        return $this->render('view', ['model' => $model]);
    }
    
    private function findModel(int $id): Category
    {
        try{
            $model          = $this->categoryRepository->get($id);
            $model->parents = $this->categoryRepository->getParents($model);
        } catch (\DomainException $e){
            throw new NotFoundHttpException("Not Found");
        }
        
        return $model;
    }
}
