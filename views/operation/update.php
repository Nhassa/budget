<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Currency;
use app\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = 'Изменить транзакцию от ' . ' ' . $model->created_at;
$this->params['breadcrumbs'][] = ['label' => 'Транзакции', 'url' => ['create', 't' => $t]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="operation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="operation-form">

    <?php $form = ActiveForm::begin([
		'id' => 'operation-form',
		'options' => ['class' => 'form-horizontal col-lg-5'],
		'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-1\"></div><div class=\"col-lg-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
	]); ?>
	
	<?php 
	$currencies = Currency::find()->all();
	$currency_items = ArrayHelper::map($currencies,'id','iso');
	
	$operation_type = ($t === 'income') ? 0 : 1;
	$categories = Category::findAll([
		'type' => $operation_type,
	]);
	$category_items = ArrayHelper::map($categories,'id','name');
	$category_label = ($t === 'income') ? 'Статья доходов' : 'Статья расходов';
	?>
	
	<?= $form->field($model, 'currency_id')->dropDownList($currency_items)->label('Валюта') ?>
	<?= $form->field($model, 'summ') ?>
	<?= $form->field($model, 'category_id')->dropDownList($category_items)->label($category_label) ?>
	<?= $form->field($model, 'description') ?>
	
	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>
	
</div>

</div>