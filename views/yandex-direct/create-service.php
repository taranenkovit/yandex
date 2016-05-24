<?php
/*	Создание сервиса пользователя*/
/* @var $this yii\web\View */
/* @var $model_create_service \backend\modules\models\CreateServiceForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Создание сервиса';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Раздел создания нового сервиса Яндекс -->
<div class="site-create-service">
	<h2 class="text-center"><?= $this->title; ?></h2>	
	<hr>	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php $form = ActiveForm::begin([
						'method' => 'post',
						'action' => ['/yandex/yandex-direct/create-service'], 
						'options' => ['enctype' => 'multipart/form-data']
					]); 
			?>
				<div class='row'>
					<div class='form-group col-xs-5 col-sm-5 col-md-5 col-lg-5'>													
						<?= $form->field($model_create_service, 'login')->textInput(['autofocus' => true])->label('Логин на Яндексе:'); ?>	
						<?= $form->field($model_create_service, 'client_id')->textInput()->label('ID:'); ?>
						<?= $form->field($model_create_service, 'client_secret')->textInput()->label('Пароль:'); ?>	
					</div>
				</div>
				<div class='row'>
					<div class='form-group col-xs-5 col-sm-5 col-md-5 col-lg-5'>
						<?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-xs']) ?>
					</div>
				</div>		
			<?php ActiveForm::end(); ?>
		</div>
	</div>			
</div>
<!-- Конец. Раздел создания нового сервиса Яндекс -->
