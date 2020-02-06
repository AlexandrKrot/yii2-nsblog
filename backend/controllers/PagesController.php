<?php

namespace koperdog\yii2nsblog\backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use koperdog\yii2nsblog\useCases\PageService;
use koperdog\yii2nsblog\models\Page;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use koperdog\yii2nsblog\repositories\{
    PageRepository,
    CategoryRepository
};
use \koperdog\yii2nsblog\models\forms\PageForm;
use \koperdog\yii2sitemanager\components\Domains;
use \koperdog\yii2sitemanager\components\Languages;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PagesController extends Controller
{
    private $pageService;
    private $pageRepository;
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
        PageService $pageService,
        PageRepository $pageRepository,
        CategoryRepository $categoryRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        
        $this->pageService        = $pageService;
        $this->pageRepository     = $pageRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
                
        $categoriesProvider = new ArrayDataProvider([
            'allModels' => $this->categoryRepository->getAll($domain_id, $language_id)
        ]);
                
        $dataProvider = $this->pageRepository->search(\Yii::$app->request->queryParams, $domain_id, $language_id);
        
        return $this->render('index', [
            'searchForm'        => $this->pageRepository->getSearchModel(),
            'dataProvider'      => $dataProvider,
            'categoryProvider'  => $categoriesProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new PageForm();
        
        $allCategories = $this->findCategories();
        $allPages      = $this->findPages();
        
        if (
            $form->load(Yii::$app->request->post()) && $form->validate()
            && $form->pageContent->load(Yii::$app->request->post()) && $form->pageContent->validate()
        )
        {   
            if($model = $this->pageService->create($form)){
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
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $domain_id   = Domains::getEditorDomainId();
        $language_id = Languages::getEditorLangaugeId();
        
        $model = $this->findModel($id, $domain_id, $language_id);
                
        $form  = new PageForm();
        $form->loadModel($model);
        
        $allCategories = $this->findCategories();
        $allPages      = $this->findPages($id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()
            && $form->pageContent->load(Yii::$app->request->post()) && $form->pageContent->validate()) {
            if($this->pageService->save($model, $form, $domain_id, $language_id)){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success update'));
                return $this->refresh();
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error update'));
            }
        }
        else if(Yii::$app->request->post() && (!$form->validate() || $form->pageContent->validate())){
            \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Fill in required fields'));
        }

        return $this->render('update', [
            'model' => $form,
            'allCategories' => $allCategories,
            'allPages' => $allPages
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->pageRepository->delete($model);
        
        return $this->redirect(['index']);
    }
    
    private function findCategories($id = null): ?array
    {
        return ArrayHelper::map(CategoryRepository::getAll($id), 'id', 'categoryContent.name');
    }
    
    private function findPages($id = null):?array
    {
        return ArrayHelper::map(PageRepository::getAll($id), 'id', 'name');
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $domain_id = null, $language_id = null)
    {
        if(!$model = $this->pageRepository->get($id, $domain_id, $language_id)){
            throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
        }
        
        return $model;
    }
}
