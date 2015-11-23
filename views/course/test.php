<?php 

use yii\helpers\Html;

$this->title = 'Тест';
?>
<div class="test">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<p>getDate: <?php echo $model->getDate(); ?></p>
	<p>checkCourse for id=1: <?php echo $model->checkCourse(1) ? 'true' : 'false'; ?></p>
	<p>getCourse for USD: <?php echo $model->getCourse('USD'); ?></p>
</div>