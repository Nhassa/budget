<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Currency;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

$this->title = 'Отчет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = ActiveForm::begin([
		'id' => 'dateinterval-form',
		'options' => ['class' => 'form-inline'],
		'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
	]); ?>
	<?= $form->field($model, 'date_from')->widget(\yii\jui\DatePicker::classname(), [
		'language' => 'ru',
		'dateFormat' => 'yyyy-MM-dd',
	])->label('С:') ?>
	<?= $form->field($model, 'date_to')->widget(\yii\jui\DatePicker::classname(), [
			'language' => 'ru',
			'dateFormat' => 'yyyy-MM-dd',
	])->label('По:') ?>
	
	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
        </div>
    </div>
	<?php ActiveForm::end(); ?>
	
	<?php if(isset($report)) : ?>
	<h2>Отчет за период с <?= Html::encode($date_from) ?> по <?= Html::encode($date_to) ?></h2>
	
	<div class="col-lg-6">
		<h3>Доходы:</h3>
		
		<?php $income = 0;
		foreach ($report as $cat){ ?>
			<?php if ($cat['type'] === '0'){ 
			$income += $cat['sum']; ?>
			
		<p><?= $cat['name'] ?>: <?= $cat['sum']/100 ?> руб.</p>
		
			<?php } ?>
		<?php } ?>
		
		<p>Всего: <?= $income/100 ?> руб.</p>
	</div>
	
	<div class="col-lg-6">
		<h3>Расходы:</h3>
		
		<?php $expense = 0;
		foreach ($report as $cat){ ?>
			<?php if ($cat['type'] === '1'){
				$expense += $cat['sum']; ?>
			
		<p><?= $cat['name'] ?>: <?= $cat['sum']/100 ?> руб.</p>
		
			<?php } ?>
		<?php } ?>
		
		<p>Всего: <?= $expense/100 ?> руб.</p>
	</div>
		<?php endif; ?>
</div>
