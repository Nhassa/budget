<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php $category_label = ($model->type === 0) ? 'Статья доходов' : 'Статья расходов'; ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label($category_label) ?>

    <?= $form->field($model, 'type')->hiddenInput(['value' => $model->type]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
