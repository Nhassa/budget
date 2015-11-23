<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
	<?php
    NavBar::begin([
        'brandLabel' => 'Семейный бюджет',
        'brandUrl' => Yii::$app->homeUrl,
		'brandOptions' => [

		'class' => 'hidden',

	   ],
        'options' => [
            'class' => 'navbar',
        ],
    ]);
	
	$menuItems = [
		['label' => 'Расходы', 'url' => ['/operation/create', 't' => 'expense']],
        ['label' => 'Доходы', 'url' => ['/operation/create', 't' => 'income']],
	];
	
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left nav-tabs'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

<?= $content ?>
<?php $this->endContent(); ?>