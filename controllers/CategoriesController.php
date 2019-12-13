<?php

namespace koperdog\yii2nsblog\controllers;

use Yii;
use koperdog\yii2nsblog\models\Category;
use koperdog\yii2nsblog\models\CategorySearch;
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
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * 
     * {@inheritdoc}
     */
//    public function actions()
//    {
//        return [
//            'images-get' => [
//                'class' => 'vova07\imperavi\actions\GetImagesAction',
//                'url' => 'http://my-site.com/images/', // Directory URL address, where files are stored.
//                'path' => '@alias/to/my/path', // Or absolute path to directory where files are stored.
//                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico']], // These options are by default.
//            ],
//        ];
//    }
    
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
    
    public function actionSort()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $data = json_decode(\Yii::$app->request->post('sort'));
        $result = $this->categoryService->sort($data);
        
        return ['result' => $result];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {   
//        debug(\Yii::getAlias('@webroot'));
//        debug(\Yii::getAlias('@web'));
        
        $dataProvider = $this->categoryRepository->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchForm'     => $this->categoryRepository->getSearchModel(),
            'dataProvider'   => $dataProvider,
            'categoriesTree' => $categoriesTree,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        \Yii::$app->language = 'ru-RU';
        $model = new Category();
        
        $allCategories = yii\helpers\ArrayHelper::map($this->findCategories(), 'id', 'name');
        $allPages      = yii\helpers\ArrayHelper::map($this->findPages(), 'id', 'name');
        
        $model->author_id = \Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {   
            if(empty($model->parent_id)) $model->parent_id = 1;
            
            $parent = Category::findOne($model->parent_id);
            
            if($model->appendTo($parent)){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success create'));
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error create'));
            }
        }
        
//        debug($model);

        return $this->render('create', [
                'model' => $model,
                'allCategories' => $allCategories,
                'allPages' => $allPages,
            ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        if (!$model = Category::find()->with('additionalPages')->with('additionalCategories')->where(['id' => $id])->one()) {
            throw new NotFoundHttpException();
        }
        
        $model->addCategories = $model->additionalCategories;
        $model->addPages      = $model->additionalPages;
        
        $model->rltCategories = $model->relatedCategories;
        $model->rltPages      = $model->relatedPages;
        
        $allCategories = yii\helpers\ArrayHelper::map($this->findCategories($id), 'id', 'name');
        $allPages      = yii\helpers\ArrayHelper::map($this->findPages(), 'id', 'name');
        
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            
            $result = false;
            
            if($model->getDirtyAttributes(['parent_id']) && ($model->id != $model->parent_id)){
                if(empty($model->parent_id)) $model->parent_id = 1;
                $parent = Category::findOne($model->parent_id);
                $result = $model->appendTo($parent);
            }
            else{
                $result = $model->save();
            }
            
            if($result){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success update'));
                return $this->refresh();
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error update'));
            }
        }
        else if(Yii::$app->request->post() && !$model->validate()){
            \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Fill in required fields'));
        }

        return $this->render('update', [
            'model' => $model,
            'allCategories' => $allCategories,
            'allPages' => $allPages,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->isRoot())
            $model->deleteWithChildren();
        else 
            $model->delete();

        return $this->redirect(['index']);
    }
    
    private function findCategories(int $id = null): ?array
    {
        return Category::find()->select(['id', 'name'])->andWhere(['NOT IN', 'id', 1])->andFilterWhere(['NOT IN', 'id', $id])->all();
    }
    
    private function findPages():?array
    {
        return \koperdog\yii2nsblog\models\Page::find()->all();
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
    }
}
