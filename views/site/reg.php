<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-registration">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<p> </p>
	
	<?php $form = ActiveForm::begin([
		'id' => 'reg-form',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
	]); ?>
	
	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'name') ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	
	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>
	
</div>