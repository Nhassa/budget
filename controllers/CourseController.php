<?php

namespace app\controllers;

use Yii;
use app\models\Course;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CourseController extends Controller
{
	public function actionTest()
	{
		$model = new Course();
		
		return $this->render('test', ['model' => $model]);
	}
}