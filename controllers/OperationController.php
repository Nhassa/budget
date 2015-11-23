<?php

namespace app\controllers;

use Yii;
use app\models\Operation;
use yii\web\Controller;
use app\models\OperationForm;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class OperationController extends Controller
{
	public $layout = 'operation';
	
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'report'],
                'rules' => [
                    [
                        'actions' => ['create', 'report'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
		];
	}
	
	public function actionCreate()
	{
		$model = new OperationForm();
		
		$t = Yii::$app->request->get('t');
		switch ($t)
		{
			case 'income': $model->operation_type = $t; break;
			case 'expense': $model->operation_type = $t; break;
			default: $model->operation_type = 'expense';
		}
		
		$provider = new ActiveDataProvider([
			'query' => Operation::find()->with(['category', 'currency'])->orderBy('created_at DESC')->limit(20),
		]);
		$provider->setSort(false);
		
		if ($model->load(Yii::$app->request->post()) && $model->validate())
		{
			if ($operation = $model->addOperation())
			{
				return $this->refresh();
			}
		}
		
		return $this->render('create',[
			'model' => $model,
			'dataProvider' => $provider
		]);
	}
	
	public function actionUpdate($id, $t)
	{
		$this->layout = 'main';
		
		$model = $this->findModel($id);
		
		if(Yii::$app->request->post()){
			
			$post_data = Yii::$app->request->post();
			
			$post_data['Operation']['summ'] = $model->getSummForIn($post_data['Operation']['summ'], $t);
			
			if ($model->load($post_data) && $model->save()) {
				
				if (in_array($t, ['income', 'expense']))
					return $this->redirect(['create', 't' => $t]);
				else 
					return $this->redirect(['create']);
				
			} else {
				$model->summ = $model->getSummForOut($model->summ, $t);
				return $this->render('update', [
					'model' => $model,
					't' => $t
				]);
			}
			
        } else {
			$model->summ = $model->getSummForOut($model->summ, $t);
            return $this->render('update', [
                'model' => $model,
				't' => $t
            ]);
        }
	}
	
	public function actionDelete($id, $t)
	{
		$this->findModel($id)->delete();
		
		if (in_array($t, ['income', 'expense']))
			return $this->redirect(['create', 't' => $t]);
		else 
			return $this->redirect(['create']);
	}
	
	protected function findModel($id)
    {
        if (($model = Operation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionReport()
	{
		$this->layout = 'main';
		
		$model = new Operation();
		
		if (Yii::$app->request->post('Operation'))
		{
			$dates = Yii::$app->request->post('Operation');
			$model->date_from = $dates['date_from'];
			$model->date_to = $dates['date_to'];
			$report = $model->getReportByDates();
			
			return $this->render('report',[
				'model' => $model, 
				'report' => $report,
				'date_from' => $dates['date_from'],
				'date_to' => $dates['date_to']
			]);
		}
		
		return $this->render('report', [
			'model' => $model,
		]);
	}
}