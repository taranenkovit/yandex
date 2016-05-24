<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model_banners backend\modules\models\BannersForm */

use yii\helpers\Html;
use backend\modules\models\YandexAPI4;
//use yii\bootstrap\ActiveForm;
//use yii\widgets\Menu;

$this->title = 'Объявления компании';
$this->params['breadcrumbs'][] = ['label' => 'Сервисы Яндекс', 'url' => ['/yandex/yandex-direct/show-services']];
$this->params['breadcrumbs'][] = ['label' => 'Ваши компании', 'url' => ['/yandex/yandex-direct/campaigns', 'client_id' => YandexAPI4::$client_id,]];
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
	<div class="row">
		<div class='form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right'>
			<?= Html::a("Создать объявление", [
						'/yandex/yandex-direct/create-or-update-banners',
						'client_id' => YandexAPI4::$client_id,
						'campaign_id' => $campaign_id,
					],
					[
						'class' => 'btn btn-warning btn-xs',
						'title' => 'Создать объявление',
					]
				);
			?>
		</div>
	</div>
	<div class="row">
		<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
			<?php if($model_banners->list === null):?>
				<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					В компании #<?= $campaignID; ?> отсутствуют объявления!
				</div>
			<?php else: ?>
				<div class="panel panel-primary">  
					<div class="panel-heading">Объявления</div> 
					<table class="table table-striped table-hover">
						<?php foreach($model_banners->list as $banner):	?>
							<tr class="success text-center">
								<td colspan="7">
									<div class="pull-left">
									<strong>Объявление №<?= $banner->BannerID; ?></strong>
									</div>
									<div class="pull-right">
									<?= Html::a("Добавить фразу", [
												'/yandex/yandex-direct/create-or-update-banners',
												'client_id' => YandexAPI4::$client_id,
												'campaign_id' => $campaign_id,
												'banner_id' => $banner->BannerID,
											],
											[
												'class' => 'btn btn-warning btn-xs',
												'title' => 'Добавить новую фразу в объявление',
											]
										);
									?>									
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3">Заголовок объявления:</td>
								<td colspan="4"><?= $banner->Title; ?></td>
							</tr>
							<tr>
								<td colspan="3">Все внесенные изменения вступили в силу:</td>
								<td colspan="4"><?= $banner->StatusActivating; ?></td>
							</tr>						
							<tr>
								<td colspan="3">Объявление помещено в архив:</td>
								<td colspan="4"><?= $banner->StatusArchive; ?></td>
							</tr>
							<tr>
								<td colspan="3">Результат модерации объявления :</td>
								<td colspan="4"><?= $banner->StatusBannerModerate; ?></td>
							</tr>
							<tr>
								<td colspan="3">Результат модерации фраз:</td>
								<td colspan="4"><?= $banner->StatusPhrasesModerate; ?></td>
							</tr>
							<tr>
								<td colspan="3">Результат модерации визитки:</td>
								<td colspan="4"><?= $banner->StatusPhoneModerate; ?></td>
							</tr>
							<tr>
								<td colspan="3">Показ объявления включен:</td>
								<td colspan="4"><?= $banner->StatusShow; ?></td>
							</tr>
							<tr>
								<td colspan="3">Объявление активно:</td>
								<td colspan="4"><?= $banner->IsActive; ?></td>
							</tr>
							<!-- Раздел кратко о Фразах -->
							<tr class="danger text-center">
								<td colspan="7">									
									<strong>Фразы:</strong>									
								</td>
							</tr>
							<tr class="warning text-center">
								<td>Ключевая фраза</td>
								<td>Ставка на поиске Яндекса (у. е.)</td>
								<td>Результат проверки</td>
								<td>Приоритет фразы</td>
								<td>Кол-во кликов</td>
								<td>Кол-во показов</td>
								<td>Действие</td>
							</tr>
							<?php foreach($banner->Phrases as $phrase):?>
								<tr class="info text-center">
									<td><?= $phrase->Phrase;?></td>
									<td><?= $phrase->Price;?></td>
									<td>
										<?php 
											switch($phrase->StatusPhraseModerate)
											{
												case 'New':
													echo 'Не проверена';
													break;
												case 'Yes':
													echo 'Принята';
													break;
												case 'No':
													echo 'Отклонена';
													break;
											}										
										?>
									</td>
									<td>
										<?php
											switch($phrase->AutoBudgetPriority)
											{
												case 'Low':
													echo 'Низкий';
													break;
												case 'Medium':
													echo 'Средний';
													break;
												case 'High':
													echo 'Высокий';
													break;
											}
										?>
									</td>
									<td><?= $phrase->Clicks;?></td>
									<td><?= $phrase->Shows;?></td>
									<td>
										<p>
										<?= Html::a("Изменить", [
													'/yandex/yandex-direct/create-or-update-banners',
													'client_id' => YandexAPI4::$client_id,
													'campaign_id' => $campaign_id,
													'banner_id' => $banner->BannerID,
													'phrase_id' => $phrase->PhraseID,
												],
												[
													'class' => 'btn btn-success btn-xs',
													'title' => 'Изменить фразу в объявление',
												]
											);
										?>
										</p>
										<p>
										<?= Html::a("Удалить", [
													'/yandex/yandex-direct/delete-phrase',
													'client_id' => YandexAPI4::$client_id,
													'campaign_id' => $campaign_id,
													'banner_id' => $banner->BannerID,
													'phrase_id' => $phrase->PhraseID,
												],
												[
													'class' => 'btn btn-danger btn-xs',
													'title' => 'Удалить фразу в объявлении',
												]
											);
										?>
										</p>
									</td>
								</tr>
							<?php endforeach;?>							
							<!-- Конец. Раздел кратко о Фразах -->
						<?php endforeach;?>	
					</table>			
				</div>
			<?php endif; ?>
		</div>
	</div>
	
</div>
<!-- Конец. Раздел компаний в Яндекс -->