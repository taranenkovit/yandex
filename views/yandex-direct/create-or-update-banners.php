<?php
/*	Создание новой компании пользователя*/
/* @var $this yii\web\View */
/* @var $model_coub \backend\modules\models\CreateOrUpdateBannersForm */

use yii\helpers\Html;
use backend\modules\models\YandexAPI4;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

if($banner_id == 0 and $phrase_id == 0)	
	$this->title = 'Создание объявления';

if($banner_id != 0 and $phrase_id == 0)	
	$this->title = 'Создание фразы';

if($banner_id != 0 and $phrase_id != 0)	
	$this->title = 'Изменить фразу';

$this->params['breadcrumbs'][] = ['label' => 'Сервисы Яндекс', 'url' => ['/yandex/yandex-direct/show-services']];
$this->params['breadcrumbs'][] = ['label' => 'Ваши компании', 'url' => ['/yandex/yandex-direct/campaigns', 'client_id' => YandexAPI4::$client_id,]];
$this->params['breadcrumbs'][] = ['label' => 'Объявления компании', 'url' => ['/yandex/yandex-direct/banners', 'client_id' => YandexAPI4::$client_id, 'campaign_id' => $campaign_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Раздел создание/обновление объявления/фразы -->
<div class="site-create-or-update-banners">
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
						'action' => [
							'/yandex/yandex-direct/create-or-update-banners', 
							'client_id' => YandexAPI4::$client_id,
							'campaign_id' => $campaign_id, 
							'banner_id' => $banner_id, 
							'phrase_id' => $phrase_id,
						], 
						'options' => ['enctype' => 'multipart/form-data']
					]); 
			?>
				
				<div class='row'>
					<div class='form-group col-xs-12 col-sm-12 col-md-12 col-lg-12'>													
						<!-- Раздел BannerInfo -->
						<div class="row" >
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3'>
								<?= $form->field($model_coub, 'banner_id')
										->hiddenInput(['value' => $banner_id])
										->label(''); ?>	
								
								<?= $form->field($model_coub, 'campaign_id')
										->hiddenInput(['value' => $campaign_id])
										->label(''); ?>	
								
								<?= $form->field($model_coub, 'title')
										->textInput()
										->label('Заголовок объявления'); ?>	
									
								<?= $form->field($model_coub, 'text')
										->textInput()
										->label('Текст объявления'); ?>	
								
								<?= $form->field($model_coub, 'href')
										->textInput()
										->label('Ссылка на сайт рекламодателя'); ?>	
							
								<?= $form->field($model_coub, 'geo')
										->dropDownList(ArrayHelper::map($model_coub->regions, 'RegionID', 'RegionName'))
										->label('Идентификаторы регионов, для которых показы включены или выключены:'); ?>
								
								<?= $form->field($model_coub, 'ogrn')
										->textInput()
										->label('Код ОГРН для юридических лиц'); ?>
							</div>
							<!-- Конец. Раздел BannerInfo -->
						</div>						
					
						
						<div class="row" >
							<legend>Контактные данные рекламодателя:</legend>
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								
								<?= $form->field($model_coub, 'contact_person')
										->textInput()
										->label('Контактное лицо:'); ?>
									
								<?= $form->field($model_coub, 'country')
										->textInput()
										->label('Страна:'); ?>
										
								<?= $form->field($model_coub, 'country_code')
										->textInput()
										->label('Телефонный код страны:'); ?>
								
								<?= $form->field($model_coub, 'city')
										->textInput()
										->label('Город:'); ?>
								
								<?= $form->field($model_coub, 'street')
										->textInput()
										->label('Улица:'); ?>
								
								<?= $form->field($model_coub, 'house')
										->textInput()
										->label('Номер дома:'); ?>
								
								<?= $form->field($model_coub, 'build')
										->textInput()
										->label('Номер строения или корпуса:'); ?>
							
								<?= $form->field($model_coub, 'apart')
										->textInput()
										->label('Номер квартиры или офиса:'); ?>
							</div>
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								
								<?= $form->field($model_coub, 'city_code')
										->textInput()
										->label('Телефонный код города:'); ?>
								
								<?= $form->field($model_coub, 'phone')
										->textInput()
										->label('Телефонный номер для связи:'); ?>
								
								<?= $form->field($model_coub, 'phone_ext')
										->textInput()
										->label('Добавочный телефонный номер для соединения через офисную АТС:'); ?>
								
								<?= $form->field($model_coub, 'company_name')
										->textInput()
										->label('Название организации:'); ?>
								
								<?= $form->field($model_coub, 'im_client')
											->dropDownList(['' => 'Нет', 'icq' => 'icq', 'jabber' => 'jabber', 'skype' => 'skype', 'mail_agent' => 'mail_agent'])
											->label('Тип сети мгновенного обмена сообщениями:'); ?>
								
								<?= $form->field($model_coub, 'im_login')
										->textInput()
										->label('Логин в сети мгновенного обмена сообщениями:'); ?>
								
								<?= $form->field($model_coub, 'extra_message')
										->textInput()
										->label('Дополнительная информация о рекламируемом товаре или услуге:'); ?>
								
								<?= $form->field($model_coub, 'contact_email')
										->textInput()
										->label('Адрес электронной почты:'); ?>
							</div>
						</div>
						<div class='row'>
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Режим работы организации:</legend>
								<?= $form->field($model_coub, 'work_time_day_begin')									
										->dropDownList([
											'0' => 'Пн.', 
											'1' => 'Вт.',
											'2' => 'Ср.',
											'3' => 'Чт.',
											'4' => 'Пт.',
											'5' => 'Сб.',
											'6' => 'Вс.',
										])
										->label('С дня недели:'); ?>							
										
								<?= $form->field($model_coub, 'work_time_hours_begin')									
										->dropDownList(range(0,23,1))
										->label('Часы, начала рабочего дня:'); ?>
										
								<?= $form->field($model_coub, 'work_time_minuts_begin')								
										->dropDownList(['0' => '0', '15' => '15', '30' => '30', '45' => '45'])
										->label('Минуты, начала рабочего дня:'); ?>
								
								<?= $form->field($model_coub, 'work_time_day_end')								
										->dropDownList([
											'0' => 'Пн.', 
											'1' => 'Вт.',
											'2' => 'Ср.',
											'3' => 'Чт.',
											'4' => 'Пт.',
											'5' => 'Сб.',
											'6' => 'Вс.',
										])
										->label('По день недели:'); ?>
										
								
								<?= $form->field($model_coub, 'work_time_hours_end')									
										->dropDownList(range(0,23,1))
										->label('Часы, окончания рабочего дня:'); ?>
								
								<?= $form->field($model_coub, 'work_time_minuts_end')									
										->dropDownList(['0' => '0', '15' => '15', '30' => '30', '45' => '45'])
										->label('Минуты, окончания рабочего дня:'); ?>
							</div>	
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6'>
								<legend>Координаты местоположения клиента:</legend>
								
								<?= $form->field($model_coub, 'x')
										->textInput(['title'=>'От -180 до 180'])
										->label('Долгота точки:'); ?>
								
								<?= $form->field($model_coub, 'y')
										->textInput(['title'=>'От -90 до 90'])
										->label('Широта точки:'); ?>
								
								<?= $form->field($model_coub, 'x1')
										->textInput(['title'=>'От -180 до 180'])
										->label('Долгота левого нижнего угла области на карте:'); ?>
										
								<?= $form->field($model_coub, 'y1')
										->textInput(['title'=>'От -90 до 90'])
										->label('Широта левого нижнего угла области на карте:'); ?>
								
								<?= $form->field($model_coub, 'x2')
										->textInput(['title'=>'От -180 до 180'])
										->label('Долгота правого верхнего угла области на карте:'); ?>
								
								<?= $form->field($model_coub, 'y2')
										->textInput(['title'=>'От -90 до 90'])
										->label('Широта правого верхнего угла области на карте:'); ?>
							</div>
						</div>
					
						<div class="row" >
							<div class='form-group col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3'>
								<legend>Информация о фразе:</legend>
									<?= $form->field($model_coub, 'old_phrase')
											->hiddenInput()
											->label(''); ?>
											
									<?= $form->field($model_coub, 'phrase_id')
											->hiddenInput(['value' => $phrase_id])
											->label(''); ?>
									
									<?= $form->field($model_coub, 'phrase')
											->textInput()
											->label('Ключевая фраза:'); ?>
											
									<?= $form->field($model_coub, 'is_rubric')
											->dropDownList(['No' => 'Нет', 'Yes' => 'Да'])
											->label('Фраза является рубрикой Яндекс:'); ?>
									
									<?= $form->field($model_coub, 'price')
											->textInput()
											->label('Ставка на поиске Яндекса (у. е.):'); ?>
											
									<?= $form->field($model_coub, 'context_price')
											->textInput()
											->label('Ставка в Рекламной сети Яндекса (у. е.):'); ?>
									
									<?= $form->field($model_coub, 'auto_broker')
											->dropDownList(['Выключить' => 'Выключить', 'Включить' => 'Включить'])
											->label('Автоброкер:'); ?>
									
									<?= $form->field($model_coub, 'auto_budget_priority')
											->dropDownList([
												'Low' => 'Низкий приоритет', 
												'Medium' => 'Средний приоритет',
												'High' => 'Высокий приоритет',
											])
											->label('Приоритет фразы при использовании автоматических стратегий:'); ?>
									
							</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='form-group col-xs-5 col-sm-5 col-md-5 col-lg-5'>
						<?= Html::submitButton($this->title, ['class' => 'btn btn-primary btn-xs']) ?>
					</div>
				</div>		
			<?php ActiveForm::end(); ?>
		</div>
	</div>			
</div>
<!-- Конец. Раздел создание/обновление объявления/фразы -->
