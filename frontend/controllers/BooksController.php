<?php

namespace frontend\controllers;

use Yii;
use app\models\Books;
use app\models\BooksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        // Сохранение ссылки для возврата
        $session = new Session;
        $session->open();
        $session['urlParam'] = Yii::$app->request->queryString;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Books();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'image');
            $model->image = $file;  
            
            // Если изображение выбрано, загрузить его и удалить старое
            if (!empty($model->image)) {
                $filename = uniqid();
                $model->image = $file;
                $model->preview = $filename.".".$model->image->getExtension();
                if ($model->save()) {
                    $file->saveAs( $model->getUplDir() . $filename . "." . $model->image->getExtension() );
                    return $this->redirect(["index"]);
                }
            } else {
                if ($model->save()) {
                    return $this->redirect(["index"]);
                }
            }
        }
        return $this->render('create',[
             'model' => $model,
           ]);
    }
    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $session = new Session;
        $session->open();

        $oldFile = $model->preview;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'image');
            $model->image = $file;  
            
            // Если изображение выбрано, загрузить его и удалить старое
            if (!empty($model->image)) {
                $filename = uniqid();
                $model->image = $file;
                $model->preview = $filename.".".$model->image->getExtension();
                if ($model->save()) {
                    $file->saveAs( $model->getUplDir() . $filename . "." . $model->image->getExtension() );
                    if (file_exists($model->getUplDir() . $oldFile)) {
                        @unlink($model->getUplDir() . $oldFile);  
                    }
                    return $this->redirect("?".$session['urlParam']);
                }
            } else {
                if ($model->save()) {
                    return $this->redirect("?".$session['urlParam']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
