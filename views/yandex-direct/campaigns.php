<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model_campaigns backend\modules\models\CampaignsForm */

use yii\helpers\Html;
use backend\modules\models\YandexAPI4;
//use yii\bootstrap\ActiveForm;
//use yii\widgets\Menu;

$this->title = 'Ваши компании';
$this->params['breadcrumbs'][] = ['label' => 'Сервисы Яндекс', 'url' => ['/yandex/yandex-direct/show-services']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Раздел компаний в Яндекс -->
<div class="site-yandex">
	<h2 class="text-center"><?= $this->title; ?></h2>	
	<hr>
	<?php if(isset($massage_error)):?>
		<div class="alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			Ошибка!<?= $massage_error; ?>
		</div>
	<?php endif; ?>
	<?php if($model_campaigns->list->data !== null):?>
		<div class="row">
			<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
				<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
					<?= Html::a("Создать компанию", [
								'/yandex/yandex-direct/create-or-update-campaign',
								'client_id' => YandexAPI4::$client_id,
								'campaignID' => 0,
							],
							[
								'class' => 'btn btn-success btn-xs',
								'title' => 'Создать компанию',
							]
						);
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
			<?php if($model_campaigns->list->data === null):?>
				<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					У сервиса отсутствуют компании или сервис не имеет токена!
				</div>
			<?php else: ?>
				<div class="panel panel-primary">  
					<div class="panel-heading">Компании</div> 
					<table class="table table-striped table-hover">
						<?php foreach($model_campaigns->list->data as $campaign):	?>
							<tr class="success text-center">
								<td colspan="2">
									<div class="pull-left">
									<strong>Компания №<?= $campaign->CampaignID; ?></strong>
									</div>
									<div class="pull-right">
									<?= Html::a("Объявления", [
												'/yandex/yandex-direct/banners',
												'client_id' => YandexAPI4::$client_id,
												'campaign_id' => $campaign->CampaignID,
											],
											[
												'class' => 'btn btn-warning btn-xs',
												'title' => 'Просмотреть объявления компании',
											]
										);
									?>
									<?= Html::a("Изменить", [
												'/yandex/yandex-direct/create-or-update-campaign',
												'client_id' => YandexAPI4::$client_id,
												'campaignID' => $campaign->CampaignID,
											],
											[
												'class' => 'btn btn-warning btn-xs',
												'title' => 'Изменить компанию',
											]
										);
									?>
									<?php if($campaign->StatusShow == 'No'): ?>
										<?= Html::a("Показывать", [
													'/yandex/yandex-direct/resume-campaign',
													'client_id' => YandexAPI4::$client_id,
													'campaignID' => $campaign->CampaignID,
												],
												[
													'class' => 'btn btn-warning btn-xs',
													'title' => 'Разрешить показывать компанию',
												]
											);
										?>
									<?php else:?>
										<?= Html::a("Остановить", [
													'/yandex/yandex-direct/stop-campaign',
													'client_id' => YandexAPI4::$client_id,
													'campaignID' => $campaign->CampaignID,
												],
												[
													'class' => 'btn btn-warning btn-xs',
													'title' => 'Остановить кампанию',
												]
											);
										?>
									<?php endif;?>
									
									<?php if($campaign->StatusArchive == 'No'): ?>
										<?= Html::a("Поместить в архив", [
													'/yandex/yandex-direct/archive-campaign',
													'client_id' => YandexAPI4::$client_id,
													'campaignID' => $campaign->CampaignID,
												],
												[
													'class' => 'btn btn-warning btn-xs',
													'title' => 'Поместить кампанию в архив',
												]
											);
										?>
									<?php else: ?>
										<?= Html::a("Извлечь из архива", [
													'/yandex/yandex-direct/un-archive-campaign',
													'client_id' => YandexAPI4::$client_id,
													'campaignID' => $campaign->CampaignID,
												],
												[
													'class' => 'btn btn-warning btn-xs',
													'title' => 'Извлечь кампанию из архива',
												]
											);
										?>
									<?php endif;?>
									<?php if($campaign->Status == 'Черновик'): ?>
										<?= Html::a("Удалить", [
													'/yandex/yandex-direct/delete-campaign',
													'client_id' => YandexAPI4::$client_id,
													'campaignID' => $campaign->CampaignID,
												],
												[
													'class' => 'btn btn-warning btn-xs',
													'title' => 'Удалить кампанию',
												]
											);
										?>
									<?php endif; ?>
									</div>
								</td>
							</tr>
							<tr>
								<td>Логин владельца кампании:</td>
								<td><?= $campaign->Login; ?></td>
							</tr>
							<tr>
								<td>Название кампании:</td>
								<td><?= $campaign->Name; ?></td>
							</tr>						
							<tr>
								<td>Начало показа объявлений:</td>
								<td><?= $campaign->StartDate; ?></td>
							</tr>
							<tr>
								<td>Cумма средств всей компании:</td>
								<td><?= $campaign->Sum; ?></td>
							</tr>
							<tr>
								<td>Текущий баланс:</td>
								<td><?= $campaign->Rest; ?></td>
							</tr>
							<tr>
								<td>Количество показов за время кампании:</td>
								<td><?= $campaign->Shows; ?></td>
							</tr>
							<tr>
								<td>Количество кликов за время кампании:</td>
								<td><?= $campaign->Clicks; ?></td>
							</tr>
							<tr>
								<td>Статус кампании:</td>
								<td><?= $campaign->Status; ?></td>
							</tr>
							<tr>
								<td>Показ объявлений кампании:</td>
								<td><?= ($campaign->StatusShow == 'Yes')? 'Включен':'Выключен'; ?></td>
							</tr>
							<tr>
								<td>Состояние активизации кампании:</td>
								<td><?= ($campaign->StatusShow == 'Yes')? ' Активизирована ':'Ожидается активизация'; ?></td>
							</tr>
							<tr>
								<td>Имя персонального менеджера в Яндексе:</td>
								<td><?= $campaign->ManagerName; ?></td>
							</tr>
							<tr>
								<td>Результат проверки модератором:</td>
								<td>
									<?php 
										switch($campaign->StatusModerate)
										{
											case 'Yes':
												echo 'Модератор одобрил хотя бы одно объявление';
												break;
											case 'No':
												echo 'Модератор отклонил все объявления';
												break;
											case 'New':
												echo 'Объявления не отправлялись на проверку';
												break;
											case 'Pending':
												echo 'Проводится проверка';
												break;
										}
										
									?>
								</td>							
							</tr>
							<tr>
								<td>Состояние архивации кампании:</td>
								<td>
									<?php 
										switch($campaign->StatusArchive)
										{
											case 'Yes':
												echo 'Кампания помещена в архив';
												break;
											case 'No':
												echo 'Кампания не в архиве';
												break;
											case 'Pending':
												echo 'Происходит перенос кампании в архив либо возврат из архива';
												break;										
										}									
									?>
								</td>							
							</tr>
							<tr>
								<td>Сумма, доступная для перевода с помощью метода TransferMoney:</td>
								<td><?= $campaign->SumAvailableForTransfer; ?></td>
							</tr>
							<tr>
								<td>Название рекламного агентства:</td>
								<td><?= $campaign->AgencyName; ?></td>
							</tr>
							<tr>
								<td>Статус активности компании:</td>
								<td><?= ($campaign->IsActive == 'Yes')? 'Объявления показываются ':'Объявления не показываются'; ?></td>
							</tr>
						<?php endforeach;?>	
					</table>			
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<!-- Конец. Раздел компаний в Яндекс -->
