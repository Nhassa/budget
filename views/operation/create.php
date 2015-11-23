<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Currency;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

$this->title = ($model->operation_type === 'income') ? 'Добавить доходы' : 'Добавить расходы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<p> </p>
	
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
	
	$operation_type = ($model->operation_type === 'income') ? 0 : 1;
	$categories = Category::findAll([
		'type' => $operation_type,
	]);
	$category_items = ArrayHelper::map($categories,'id','name');
	$category_label = ($model->operation_type === 'income') ? 'Статья доходов' : 'Статья расходов';
	?>
	
	<?= $form->field($model, 'currency_id')->dropDownList($currency_items) ?>
	<?= $form->field($model, 'summ') ?>
	<?= $form->field($model, 'category_id')->dropDownList($category_items)->label($category_label) ?>
	<?= $form->field($model, 'description') ?>
	
	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>
	
	<div class="col-lg-1">
		<div class="like-field"></div>
		<div class="like-field"></div>
		<div class="like-field"><?= Html::a('+', ['/category/create', 't' => $model->operation_type], ['class' => 'btn btn-default', 'title' => ($model->operation_type === 'income') ? 'Добавить статью доходов' : 'Добавить статью расходов']) ?></div>
	</div>
	
	<div class="col-lg-12">
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'columns' => [
			'created_at',
			[
				'attribute' => 'summ',
				'label' => 'Сумма',
				'content' => function($data){
					return $data->summ / 100;
				}
			],
			'currency.iso',
			'category.name',
			'description',
			[
				'class' => 'yii\grid\ActionColumn',
				'header'=>'Действия', 
				'template' => '{update} {delete}',
				'buttons' => [
					'update' => function($url, $m) use($model) {
						return Html::a(
						'<span class="glyphicon glyphicon-pencil"></span>',
						$url.'/'.$model->operation_type,
						['title' => 'Изменить', 'data-pjax' => '0', 'aria-label' => 'Update']
						);
					},
					'delete' => function($url, $m) use($model) {
						return Html::a(
						'<span class="glyphicon glyphicon-trash"></span>',
						$url.'/'.$model->operation_type,
						['title' => 'Удалить', 'data-pjax' => '0', 'aria-label' => 'Delete', 'data-method' => 'post', 'data-confirm' => 'Вы уверены, что хотите удалить эту транзакцию?']
						);
					}
				]
			],
		],
    ]); ?>
	
	</div>
</div>