<?php

namespace app\modules\str\controllers;

use app\components\Debug;
use app\modules\str\models\Jobtypes;
use app\modules\str\models\Project;
use Yii;
use app\modules\str\models\Order;
use app\modules\str\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\str\models\Room;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post())) {

            $id_room = intval(Yii::$app->request->get('id'));
            $id_project = Room::find()->where(['id' => $id_room])->one();
            $model->id_room = $id_room;
            //echo Debug::d($id_project,'$id_project'); die;
            $model->id_project = $id_project->id_parent;

            $job = Jobtypes::find()->where(['id' => $model->id_job])->one();
            //echo Debug::d($job->toArray(),'$job'); die;
            //echo Debug::d($model->toArray(),'model'); die;

            $model->price1 = $job->price1;
            $model->price2 = $job->price2;
            $model->price3 = $model->price2 - $model->price1;
            $model->summ1 = $model->price1 * $model->volume;
            $model->summ2 = ($model->price2 * $model->volume) - $model->summ1;

            //echo Debug::d($model->toArray(),'model'); die;

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $job = Jobtypes::find()->where(['id' => $model->id_job])->one();
            //echo Debug::d($job->toArray(),'$job'); die;
            //echo Debug::d($model->toArray(),'model'); die;

            $model->price1 = $job->price1;
            $model->price2 = $job->price2;
            $model->price3 = $model->price2 - $model->price1;
            $model->summ1 = $model->price1 * $model->volume;
            $model->summ2 = ($model->price2 * $model->volume) - $model->summ1;

            //echo Debug::d($model->toArray(),'model'); die;

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
