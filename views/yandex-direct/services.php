<?php
/*	Представление сервисов пользователя*/
/* @var $this yii\web\View */
/* @var $model_services \backend\modules\models\ServicesForm */

use yii\helpers\Html;

$this->title = 'Сервисы Яндекс';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Раздел создания сервисов -->
<div class="site-yandex">
	<h2 class="text-center"><?= $this->title; ?></h2>	
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
				<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
					<?= Html::a("Создать сервис", [
								'/yandex/yandex-direct/create-service', 					
							],
							[
								'class' => 'btn btn-success btn-xs',
								'title' => 'Создать сервис',
							]
						);
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<?php if($model_services->services === null):?>
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Создайте сервис.
						</div>
					<?php else: ?>
						<div class="panel panel-primary">  								
							<div class="panel-heading">Сервисы</div> 
							<table class="table table-striped table-hover text-center">
								<tr class="success">
									<td>№</td>
									<td>Логин</td>
									<td>Id клиента</td>
									<td>Secret клиента</td>
									<td>Токен</td>							
								</tr>
								<?php foreach($model_services->services as $service):	?>
									<tr>
										<td><?= $service['id']; ?></td>							
										<td>
											<?= Html::a("<span class='badge' title='Открыть компании'>".$service['login']."</span>", [
												'/yandex/yandex-direct/campaigns', 
												'client_id' => $service['client_id'],
												]									
											);
											?>
										</td>
										<td><?= $service['client_id'];?></td>
										<td><?= $service['client_secret'];?></td>							
										<td>
											<?= Html::a("<span class='badge' title='".(($service['token'] == null)? 'Получить токен':'Обновить токен')."'>".(($service['token'] == null)? 'Получить':'Обновить')."</span>", [
												'/yandex/yandex-direct/response-token', 
												'client_id' => $service['client_id'],
												]								
											);
											?>
										</td>							
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Конец. Раздел создания сервисов -->
