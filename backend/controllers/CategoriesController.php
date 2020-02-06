<?php

namespace koperdog\yii2nsblog\backend\controllers;

use Yii;
use koperdog\yii2nsblog\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use koperdog\yii2nsblog\repositories\{
    PageRepository,
    CategoryRepository
};
use \koperdog\yii2nsblog\models\forms\CategoryForm;
use \koperdog\yii2sitemanager\components\Domains;
use \koperdog\yii2sitemanager\components\Languages;

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
    
    public function __construct
    (
        $id, 
        $module, 
        \koperdog\yii2nsblog\useCases\CategoryService $categoryService,
        CategoryRepository $categoryRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        
        $this->categoryService    = $categoryService;
        $this->categoryRepository = $categoryRepository;
        
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
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
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
                
        $dataProvider = $this->categoryRepository->search(\Yii::$app->request->queryParams, $domain_id, $language_id);
        
        return $this->render('index', [
            'searchForm'     => $this->categoryRepository->getSearchModel(),
            'dataProvider'   => $dataProvider,
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
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
        
        return $this->render('view', [
            'model' => $this->findModel($id, $domain_id, $language_id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new CategoryForm();
        
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
        
        $allCategories = $this->findCategories($domain_id, $language_id);
        $allPages      = $this->findPages($domain_id, $language_id);
        
        if (
            $form->load(Yii::$app->request->post()) && $form->validate()
            && $form->categoryContent->load(Yii::$app->request->post()) && $form->categoryContent->validate()
        )
        {   
            if($model = $this->categoryService->create($form)){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success create'));
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error create'));
            }
        }
        
        return $this->render('create', [
                'model' => $form,
                'allCategories' => $allCategories,
                'allPages' => $allPages,
            ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be refresh.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
        
        $model = $this->findModel($id, $domain_id, $language_id);
        
        $form  = new CategoryForm();
        $form->loadModel($model);
        
        $allCategories = $this->findCategories($domain_id, $language_id, $id);
        $allPages      = $this->findPages($domain_id, $language_id);
        
        if(
            $form->load(Yii::$app->request->post()) && $form->validate()
            && $form->categoryContent->load(Yii::$app->request->post()) && $form->categoryContent->validate()){
            if($this->categoryService->save($model, $form, $domain_id, $language_id)){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success update'));
                return $this->refresh();
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error update'));
            }
        }
        else if(Yii::$app->request->post() && (!$form->validate() || !$form->categoryContent->validate())){
            \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Fill in required fields'));
        }
        
        

        return $this->render('update', [
            'model' => $form,
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
        $this->categoryRepository->delete($model);
        return $this->redirect(['index']);
    }
    
    private function findCategories($domain_id = null, $language_id = null, $id = null): ?array
    {
        return ArrayHelper::map(CategoryRepository::getAll($domain_id, $language_id, $id), 'id', 'categoryContent.name');
    }
    
    private function findPages($domain_id = null, $language_id = null, $id = null):?array
    {
        return ArrayHelper::map(PageRepository::getAll($domain_id, $language_id, $id), 'id', 'pageContent.name');
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $domain_id = null, $language_id = null)
    {
        try{
            $model = $this->categoryRepository->get($id, $domain_id, $language_id);
        } catch (\DomainException $e){
            throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
        }
        
        return $model;
    }
}
