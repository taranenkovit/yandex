<?php
/*	Создание новой компании пользователя*/
/* @var $this yii\web\View */
/* @var $model_coгc \backend\modules\models\CreateOrUpdateCampaignForm */

use yii\helpers\Html;
use backend\modules\models\YandexAPI4;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Создание компании';
$this->params['breadcrumbs'][] = ['label' => 'Сервисы Яндекс', 'url' => ['yandex/yandex-direct/show-services']];
$this->params['breadcrumbs'][] = ['label' => 'Ваши компании', 'url' => ['/yandex/yandex-direct/campaigns', 'client_id' => YandexAPI4::$client_id,]];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Раздел создания нового сервиса Яндекс -->
<div class="site-create-service">
	<h2 class="text-center"><?= $this->title; ?></h2>	
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php if (isset($massage_error)): ?>
				<div class="alert alert-danger">
					<strong>Ошибка!</strong> <?= $massage_error; ?>
				</div>
			<?php endif; ?>
			<?php $form = ActiveForm::begin([
						'method' => 'post',
						'action' => ['/yandex/yandex-direct/create-or-update-campaign', 'client_id' => YandexAPI4::$client_id], 
						'options' => ['enctype' => 'multipart/form-data']
					]); 
			?>
				
				<div class='row'>
					<div class='form-group col-xs-12 col-sm-12 col-md-12 col-lg-12'>													
						<!-- Раздел CampaignInfo -->
						<div class="row" >
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3'>
								<?= $form->field($model_couc, 'login')
										->hiddenInput(['value' => YandexAPI4::$login])
										->label(''); ?>	
								
								<?=	$form->field($model_couc, 'campaignID')
										->hiddenInput(['value' => $campaignID])
										->label('');?>
								
								<?= $form->field($model_couc, 'name')
										->textInput(['autofocus' => true])
										->label('Название кампании:'); ?>	
								
								<?= $form->field($model_couc, 'fio')
										->textInput()
										->label('Имя и фамилия владельца кампании:'); ?>
								
								<?= $form->field($model_couc, 'startDate')
										->input('date')
										->label('Начало показа объявлений:'); ?>
							</div>
						</div>						
						<!-- Раздел CampaignStrategy -->
						<div class="row" >
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Стратегия показов на поиске:</legend>
								
								<?= $form->field($model_couc, 'strategyName')
										->dropDownList([
											'Стратегии с ручным управлением:' => [
												'HighestPosition'=>'Наивысшая доступная позиция', 
												'LowestCost' => 'Показ в блоке по минимальной цене',
												'LowestCostPremium' => 'Показ в блоке по минимальной цене (объявления показываются только в спецразмещении)',
											],
											'Автоматические стратегии:' => [
												'WeeklyBudget' => 'Недельный бюджет',
												'WeeklyPacketOfClicks' => 'Недельный пакет кликов',
												'AverageClickPrice' => 'Средняя цена клика',
											],
										],
										['id' => 'strategyName']
										)->label('Название стратегии управления ставками:'); ?>
								<?php
									$script = <<< JS
										$("#strategyName").on("click",function(e)
											{											
												var value = $("#strategyName option:selected").val();
												switch(value)
												{
													case 'HighestPosition':
														$("#maxPrice").prop('disabled', true);
														$("#averagePrice").prop('disabled', true);
														$("#weeklySumLimit").prop('disabled', true);
														$("#clicksPerWeek").prop('disabled', true);
														break;
													case 'LowestCost':
														$("#maxPrice").prop('disabled', true);
														$("#averagePrice").prop('disabled', true);
														$("#weeklySumLimit").prop('disabled', true);
														$("#clicksPerWeek").prop('disabled', true);
														break;
													case 'LowestCostPremium':
														$("#maxPrice").prop('disabled', true);
														$("#averagePrice").prop('disabled', true);
														$("#weeklySumLimit").prop('disabled', true);
														$("#clicksPerWeek").prop('disabled', true);
														break;
													case 'WeeklyBudget':
														$("#maxPrice").prop('disabled', false);
														$("#averagePrice").prop('disabled', true);
														$("#weeklySumLimit").prop('disabled', false);
														$("#clicksPerWeek").prop('disabled', true);
														break;
													case 'WeeklyPacketOfClicks':
														$("#maxPrice").prop('disabled', false);
														$("#averagePrice").prop('disabled', false);
														$("#weeklySumLimit").prop('disabled', true);
														$("#clicksPerWeek").prop('disabled', false);
														break;
													case 'AverageClickPrice':
														$("#maxPrice").prop('disabled', true);
														$("#averagePrice").prop('disabled', false);
														$("#weeklySumLimit").prop('disabled', false);
														$("#clicksPerWeek").prop('disabled', true);
														break;
												}
												
											})
JS;
									$this->registerJs($script, $this::POS_END);
								?>
								
								<?= $form->field($model_couc, 'maxPrice')
										->textInput(['id'=>'maxPrice', 'disabled'=>'disabled'])
										->label('Максимальная ставка:'); ?>
								
								<?= $form->field($model_couc, 'averagePrice')
										->textInput(['id'=>'averagePrice', 'disabled'=>'disabled'])
										->label('Средняя ставка для стратегии:'); ?>
								
								<?= $form->field($model_couc, 'weeklySumLimit')
										->textInput(['id'=>'weeklySumLimit', 'disabled'=>'disabled'])
										->label('Максимальный недельный бюджет:'); ?>
								
								<?= $form->field($model_couc, 'clicksPerWeek')
										->textInput(['id'=>'clicksPerWeek', 'disabled'=>'disabled'])
										->label('Количество кликов в неделю для:'); ?>
							</div>
						
							<!-- Конец. Раздел CampaignStrategy -->
							<!-- Раздел SmsNotificationInfo -->
						
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Параметрами отправки SMS-уведомлений:</legend>
							
								<?= $form->field($model_couc, 'metricaSms')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Сообщать результаты мониторинга сайтов по данным Яндекс.Метрики:'); ?>
								
								<?= $form->field($model_couc, 'moderateResultSms')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Сообщать результаты модерации объявлений:'); ?>
								
								<?= $form->field($model_couc, 'moneyInSms')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Сообщать о зачислении средств на баланс кампании:'); ?>
								
								<?= $form->field($model_couc, 'moneyOutSms')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Сообщать об исчерпании средств на балансе кампании:'); ?>
								
								<?= $form->field($model_couc, 'smsTimeFrom')
										->input('time', ['step'=>900])
										->label('Время, начиная с которого разрешено отправлять SMS о событиях, связанных с кампанией:'); ?>
								
								<?= $form->field($model_couc, 'smsTimeTo')
										->input('time', ['step'=>900])
										->label('Время, до которого разрешено отправлять SMS о событиях, связанных с кампанией:'); ?>
								
							</div>
							<!-- Конец. Раздел SmsNotificationInfo -->
							<!-- Раздел EmailNotificationInfo -->							
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Параметрами отправки уведомлений по эл. почте:</legend>
								<?= $form->field($model_couc, 'email')
										->input('email')
										->label('Адрес электронной почты для отправки уведомлений о событиях, связанных с кампанией:'); ?>
									
								<?= $form->field($model_couc, 'warnPlaceInterval')
											->dropDownList(['15' => '15', '30' => '30', '60' => '60'])
											->label('Периодичность проверки позиции объявления:'); ?>
								
								<?= $form->field($model_couc, 'moneyWarningValue')
										->input('number',[ 'min' => 1, 'max' => 50, 'value' => ($campaignID == 0)? 20 : $model_create_or_update_campaign->moneyWarningValue ])
										->label('Минимальный баланс, при уменьшении до которого отправляется уведомление (%):'); ?>

								<?= $form->field($model_couc, 'sendAccNews')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Сообщать о событиях, связанных с кампанией:'); ?>

								<?= $form->field($model_couc, 'sendWarn')
										->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
										->label('Отправлять уведомления по электронной почте:'); ?>									
							</div>							
							<!-- Конец. Раздел EmailNotificationInfo -->
							<!-- Раздел /* TimeTargetInfo */ -->							
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Параметрами временного таргетинга:</legend>
								
								<?= $form->field($model_couc, 'statusBehavior')
										->dropDownList(['Yes' => 'Да', 'No' => 'Нет'])
										->label('Включить поведенческий таргетинг:'); ?>
									
								<?= $form->field($model_couc, 'showOnHolidays')
										->dropDownList(['Yes' => 'Да', 'No' => 'Нет'])
										->label('Показывать объявления в праздничные нерабочие дни:'); ?>
									
								<?= $form->field($model_couc, 'holidayShowFrom')
										->input('number',[ 'min' => 0, 'max' => 23, 'value' => ($campaignID == 0)? 0 : $model_create_or_update_campaign->holidayShowFrom])
										->label('Час, начиная с которого объявления показываются в праздничные нерабочие дни:'); ?>
									
								<?= $form->field($model_couc, 'holidayShowTo')
										->input('number',[ 'min' => 0, 'max' => 23, 'value' => ($campaignID == 0)? 0 : $model_create_or_update_campaign->holidayShowTo])
										->label('Час, до которого объявления показываются в праздничные нерабочие дни:'); ?>
									
								<?= $form->field($model_couc, 'hours')
										->inline(true)
										->checkboxList(range(0,23,1))
										->label('Расписание показов по часам: '); ?>
									
								<?= $form->field($model_couc, 'days')
										->inline(true)
										->checkboxList([
											'1'=>'Пн.', 
											'2'=>'Вт.', 
											'3'=>'Ср.', 
											'4'=>'Чт.', 
											'5'=>'Пт.', 
											'6'=>'Сб.', 
											'7'=>'Вс.'
										])
										->label('Расписание показов объявления в указанные дни:'); ?>
									
								<?= $form->field($model_couc, 'timeZone')
										->dropDownList(ArrayHelper::map($model_couc->time_zones, 'TimeZone', 'Name'))
										->label('Временная зона в месте нахождения владельца рекламной кампании:'); ?>
							</div>
							
							<div class="row" >
								<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3'>
									<?= $form->field($model_couc, 'statusContextStop')
											->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
											->label('Не использовать в Рекламной сети Яндекса фразы, отключенные на поиске за низкий CTR:'); ?>
									
									<?= $form->field($model_couc, 'contextLimit')
											->dropDownList(['Default' => 'Бюджет не ограничен ', 'Limited' => 'Бюджет ограничен '])
											->label('Ограничение бюджета на показ объявлений в Рекламной сети Яндекса:'); ?>
									
									<?= $form->field($model_couc, 'contextLimitSum')
											->input('number',[ 'min' => 0, 'max' => 100, 'value' => ($campaignID == 0)? 10 : $model_couc->contextLimitSum, 'step' => '10'])
											->label('Максимальный процент бюджета на показ объявлений в Рекламной сети Яндекса:'); ?>
									
									<?= $form->field($model_couc, 'contextPricePercent')
											->input('number',[ 'min' => 10, 'max' => 100, 'value' => ($campaignID == 0)? 10 : $model_couc->contextPricePercent, 'step' => '10'])
											->label('Максимальная ставка в Рекламной сети Яндекса в процентах от ставки на поиске:'); ?>
									
									<?= $form->field($model_couc, 'autoOptimization')
											->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
											->label('Включить автоматическое уточнение фраз:'); ?>
									
									<?= $form->field($model_couc, 'statusMetricaControl')
											->dropDownList(['Yes' => 'Да', 'No' => 'Нет'])
											->label('Останавливать показы при недоступности сайта рекламодателя:'); ?>
									
									<?= $form->field($model_couc, 'disabledDomains')
										->textInput(['title' => 'Не более 1000 мест показа. Значения указывают через запятую, например site.ru,ru.example.app.'])
										->label('Список мест показа, где не нужно показывать объявления:'); ?>
									
									<?= $form->field($model_couc, 'disabledIps')
										->textInput(['title' => 'Адреса указывают через запятую, например 127.0.0.1,127.0.0.2'])
										->label('Список IP-адресов, которым не нужно показывать объявления:'); ?>
										
									<?= $form->field($model_couc, 'statusOpenStat')
											->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
											->label('При переходе на сайт рекламодателя добавлять к URL метку в формате OpenStat:'); ?>
									
									<?= $form->field($model_couc, 'considerTimeTarget')
											->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
											->label('Рассчитывать цены позиций показа без учета ставок в остановленных объявлениях конкурентов (остановлены в соответствии с расписанием):'); ?>
									
									<?= $form->field($model_couc, 'minusKeywords')
										->textInput()
										->label('Массив минус-слов, общих для всех объявлений кампании:'); ?>
									
									<?= $form->field($model_couc, 'addRelevantPhrases')
											->dropDownList(['Yes' => 'Да', 'No' => 'Нет'])
											->label('Добавлять дополнительные релевантные фразы к объявлениям:'); ?>
										
									<?= $form->field($model_couc, 'relevantPhrasesBudgetLimit')
											->input('number',[ 'min' => 10, 'max' => 100, 'value' => ($campaignID == 0)? 10 : $model_couc->relevantPhrasesBudgetLimit, 'step' => '10'])
											->label('Максимальный процент бюджета, расходуемый на клики по дополнительным релевантным фразам:'); ?>										
								</div>
							</div>
							
							<!-- Конец. Раздел CampaignInfo -->
						</div>
						<!-- Конец. Раздел SmsNotificationInfo -->
					</div>
				</div>
				<div class='row'>
					<div class='form-group col-xs-5 col-sm-5 col-md-5 col-lg-5'>
						<?= Html::submitButton(($campaignID == 0)?'Создать':'Изменить', ['class' => 'btn btn-primary btn-xs']) ?>
					</div>
				</div>		
			<?php ActiveForm::end(); ?>
		</div>
	</div>			
</div>
<!-- Конец. Раздел создания нового сервиса Яндекс -->
