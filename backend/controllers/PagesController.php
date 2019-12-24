<?php

namespace koperdog\yii2nsblog\backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use koperdog\yii2nsblog\useCases\PageService;
use koperdog\yii2nsblog\models\Page;
use yii\helpers\ArrayHelper;
use koperdog\yii2nsblog\repositories\{
    PageRepository,
    CategoryRepository
};

/**
 * PageController implements the CRUD actions for Page model.
 */
class PagesController extends Controller
{
    private $pageService;
    private $pageRepository;
    
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
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        
        $this->pageService    = $pageService;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->pageRepository->search(Yii::$app->request->queryParams);
        $searchModel  = $this->pageRepository->getSearchModel();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $form = new Page();
        
        $allCategories = $this->findCategories();
        $allPages      = $this->findPages();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if($this->pageService->create($form)){
                \Yii::$app->session->setFlash('success', \Yii::t('nsblog', 'Success create'));
                return $this->redirect(['update', 'id' => $form->id]);
            }
            else{
                \Yii::$app->session->setFlash('error', \Yii::t('nsblog/error', 'Error create'));
            }
        }

        return $this->render('create', [
            'model' => $form,
            'allCategories' => $allCategories,
            'allPages' => $allPages
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
        $model = $this->findModel($id);
        
        $allCategories = $this->findCategories();
        $allPages      = $this->findPages($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($this->pageService->save($model)){
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
        return ArrayHelper::map(CategoryRepository::getAll($id), 'id', 'name');
    }
    
    private function findPages($id = null):?array
    {
        return ArrayHelper::map(\koperdog\yii2nsblog\repositories\PageRepository::getAll($id), 'id', 'name');
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(!$model = $this->pageRepository->get($id)){
            throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
        }
        
        return $model;
    }
}
