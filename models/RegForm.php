<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Registration form
 */
 class RegForm extends Model
 {
	 public $username;
	 public $password;
	 public $name;
	 
	 public function rules()
	 {
		 return [
			[['username', 'password', 'name'], 'required'],
			['username', 'string', 'min' => 2, 'max' =>255],
			['password', 'string', 'min' => 6, 'max' =>255],
			['username', 'unique', 
				'targetClass' => User::className(),
				'message' => 'Этот логин уже занят.'],
			
		 ];
	 }
	 
	 public function attributeLabels()
	 {
		 return [
			'username' => 'Логин',
			'name' => 'Имя',
			'password' => 'Пароль'
		 ];
	 }
	 
	 public function reg()
	 {
		 $user = new User();
		 $user->username = $this->username;
		 $user->name = $this->name;
		 $user->setPassword($this->password);
		 $user->generateAuthKey();
		 return $user->save() ? $user : null;
	 }
 }