<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

/**
 * Operation form
 */
 class OperationForm extends Model
 {
	 public $category_id;
	 public $currency_id;
	 public $summ;
	 public $description;
	 public $operation_type = 'expense';
	 
	 public function rules()
	 {
		 return [
			[['category_id', 'currency_id', 'summ'], 'required'],
            [['category_id', 'currency_id'], 'integer'],
			[['summ'], 'number'],
            [['description'], 'string', 'max' => 100]
		 ];
	 }
	 
	 public function attributeLabels()
	 {
		 return [
			'currency_id' => 'Валюта',
			'summ' => 'Сумма',
			'category_id' => 'Категория',
			'description' => 'Описание'
		 ];
	 }
	 
	 public function addOperation()
	 {
		if ($this->currency_id != '1')
		 {
			 $course = new Course();
			 if(!$course->checkCourse($this->currency_id))
			 {
				 $course->currency_id = $this->currency_id;
				 $currency = Currency::findOne($this->currency_id);
				 $course->course = $course->getCourse($currency->iso);
				 
				 if(!$course->save())
				 {
					 throw new ErrorException('Курс не обновлен'.var_dump($course));
				 }
					
			 }
		 }
		 
		 $operation = new Operation();
		 
		 $operation->user_id = Yii::$app->getUser()->id;
		 $operation->category_id = ($this->category_id === '') ? 1 : $this->category_id;
		 $operation->currency_id = ($this->currency_id === '') ? 1 : $this->currency_id;
		 $operation->summ = ($this->operation_type == 'income') ? ($this->summ * 100) : ($this->summ * -100);
		 $operation->description = $this->description;
		
		 return $operation->save() ? $operation : null;
	 }
 }