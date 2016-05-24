<?php
namespace backend\modules\models;

use Yii;
use yii\base\Model;

/**
 *	Модель создания компании
 */
class CreateOrUpdateCampaignForm extends Model
{	
	public $time_zones;
	// CampaignInfo 
	public $login;
	public $campaignID; 
	public $name;
	public $fio;
	public $startDate;
	// CampaignStrategy 
	public $strategyName;
	public $maxPrice;
	public $averagePrice;
	public $weeklySumLimit;
	public $clicksPerWeek;
	// SmsNotificationInfo 
	public $metricaSms;
	public $moderateResultSms;
	public $moneyInSms;
	public $moneyOutSms;
	public $smsTimeFrom;
	public $smsTimeTo;
	// EmailNotificationInfo
	public $email;
	public $warnPlaceInterval;
	public $moneyWarningValue;
	public $sendAccNews;
	public $sendWarn;
	public $statusBehavior;
	// TimeTargetInfo 
	public $showOnHolidays;
	public $holidayShowFrom;
	public $holidayShowTo;
	// TimeTargetItem 
	public $hours;
	public $days;
	public $timeZone;
	public $statusContextStop;
	public $contextLimit;
	public $contextLimitSum;
	public $contextPricePercent;
	public $autoOptimization;
	public $statusMetricaControl;
	public $disabledDomains;
	public $disabledIps;
	public $statusOpenStat;
	public $considerTimeTarget;
	public $minusKeywords;
	public $addRelevantPhrases;
	public $relevantPhrasesBudgetLimit;
	
	/**
	 *	Сценарии проверки введенных и полученных данных
	 */
	public function rules()
	{
		return [			
			[
				[	
					'startDate',
					'statusBehavior',
					'statusContextStop',
					'contextLimit',
					'contextLimitSum',
					'contextPricePercent',
					'autoOptimization',
					'statusMetricaControl',
					'disabledDomains',
					'disabledIps',
					'statusOpenStat',
					'considerTimeTarget',
					'minusKeywords',
					'addRelevantPhrases',
					'relevantPhrasesBudgetLimit',
					
					'maxPrice',
					
					'metricaSms',
					'moderateResultSms',
					'moneyInSms',
					'moneyOutSms',
					'smsTimeFrom',
					'smsTimeTo',
					
					'sendAccNews',
					'sendWarn',
					
					'showOnHolidays',
					'holidayShowFrom',
					'holidayShowTo',
					'timeZone',
				], 
				'default'
			],
			[
				[
					'login',
					'campaignID',
					'name',
					'fio',					
				
					'strategyName',					
					'averagePrice',
					'weeklySumLimit',
					'clicksPerWeek',
							
					'email',
					'warnPlaceInterval',
					'moneyWarningValue',

					'hours',
					'days',					
				], 
				'required', 'message' =>'Необходимо заполнить это поле!'
			],
			['email', 'email', 'message' => 'Адрес эл. почты не соответствует формату!'],
		];
	}
	/**
	 *	Получить временную зону
	 */
	public function getTimeZones()
	{		
		$result = YandexAPI4::queryJSON('GetTimeZones', []);
		$this->time_zones = $result->data;
	}
		
	/**
	 *	Добавить или обновить компанию
	 */
	public function createOrUpdateCampaign()
	{		
		$param = [
			"Login" => $this->login,
			"CampaignID" => $this->campaignID,
			"Name" => $this->name,
			"FIO" => $this->fio,
			"StartDate" => $this->startDate,
			"Strategy" => [
				/* CampaignStrategy */
				"StrategyName" => $this->strategyName,
				"MaxPrice" => $this->maxPrice,
				"AveragePrice" => $this->averagePrice,
				"WeeklySumLimit" => $this->weeklySumLimit,
				"ClicksPerWeek" => $this->clicksPerWeek,
			],
			"SmsNotification" => [
				/* SmsNotificationInfo */
				"MetricaSms" => $this->metricaSms,
				"ModerateResultSms" => $this->moderateResultSms,
				"MoneyInSms" => $this->moneyInSms,
				"MoneyOutSms" => $this->moneyOutSms,
				"SmsTimeFrom" => $this->smsTimeFrom,
				"SmsTimeTo" => $this->smsTimeTo,
			],
			"EmailNotification" => [
				/* EmailNotificationInfo */
				"Email" => $this->email,
				"WarnPlaceInterval" => $this->warnPlaceInterval,
				"MoneyWarningValue" => $this->moneyWarningValue,
				"SendAccNews" => $this->sendAccNews,
				"SendWarn" => $this->sendWarn,
			],
			"StatusBehavior" => $this->statusBehavior,
			"TimeTarget"=> [
				/* TimeTargetInfo */
				"ShowOnHolidays" => $this->showOnHolidays,
				"HolidayShowFrom" => $this->holidayShowFrom,
				"HolidayShowTo" => $this->holidayShowTo,
				"DaysHours" => [
					/* TimeTargetItem */
					[
						"Hours" => array_map("intval", $this->hours),
						"Days" => array_map("intval", $this->days),
					],
				],            			
				"TimeZone" => $this->timeZone,
			],
			"StatusContextStop" => $this->statusContextStop,
			"ContextLimit" => $this->contextLimit,
			"ContextLimitSum" => $this->contextLimitSum,
			"ContextPricePercent" => $this->contextPricePercent,
			"AutoOptimization" => $this->autoOptimization,
			"StatusMetricaControl" => $this->statusMetricaControl,
			"DisabledDomains" => $this->disabledDomains,
			"DisabledIps" => $this->disabledIps,
			"StatusOpenStat" => $this->statusOpenStat,
			"ConsiderTimeTarget" => $this->considerTimeTarget,
			"MinusKeywords" => explode(',', $this->minusKeywords),
			"AddRelevantPhrases" => $this->addRelevantPhrases,
			"RelevantPhrasesBudgetLimit" => $this->relevantPhrasesBudgetLimit,   
		];
		
		return YandexAPI4::queryJSON('CreateOrUpdateCampaign', $param);		
	}
		
	/**
	 *	Получить информацию о компании
	 *
	 *	@param integer $campaignID идентификатор кампании 
	 */	
	public function getCampaignsParams($campaignID)
	{		
		$param = [
			"CampaignIDS" => [
				$campaignID, 
			]
		];
		
		$result = YandexAPI4::queryJSON('GetCampaignsParams', $param);
		$result = $result->data[0];
		if(isset($result->IsActive))
		{
			$this->login = $result->Login;
			$this->campaignID = $result->CampaignID;
			$this->name = $result->Name;
			$this->fio = $result->FIO;
			$this->startDate = $result->StartDate;
			
			$this->strategyName = $result->Strategy->StrategyName;
			$this->maxPrice = $result->Strategy->MaxPrice;
			$this->averagePrice = $result->Strategy->AveragePrice;
			$this->weeklySumLimit = $result->Strategy->WeeklySumLimit;
			$this->clicksPerWeek = $result->Strategy->ClicksPerWeek;
			
			
			$this->metricaSms = $result->SmsNotification->MetricaSms;
			$this->moderateResultSms = $result->SmsNotification->ModerateResultSms;
			$this->moneyInSms = $result->SmsNotification->MoneyInSms;
			$this->moneyOutSms = $result->SmsNotification->MoneyOutSms;
			$this->smsTimeFrom = $result->SmsNotification->SmsTimeFrom;
			$this->smsTimeTo = $result->SmsNotification->SmsTimeTo;
			
			$this->email = $result->EmailNotification->Email;
			$this->warnPlaceInterval = $result->EmailNotification->WarnPlaceInterval;
			$this->moneyWarningValue = $result->EmailNotification->MoneyWarningValue;
			$this->sendAccNews = $result->EmailNotification->SendAccNews;
			$this->sendWarn = $result->EmailNotification->SendWarn;
			
			$this->statusBehavior = $result->StatusBehavior;
			
			$this->showOnHolidays = $result->TimeTarget->ShowOnHolidays; 
			$this->holidayShowFrom = $result->TimeTarget->HolidayShowFrom;
			$this->holidayShowTo = $result->TimeTarget->HolidayShowTo;
			$this->hours = $result->TimeTarget->DaysHours[0]->Hours;
			$this->days = $result->TimeTarget->DaysHours[0]->Days;
			
			$this->timeZone = $result->TimeTarget->TimeZone;
			
			$this->statusContextStop = $result->StatusContextStop;
			$this->contextLimit = $result->ContextLimit;
			$this->contextLimitSum = $result->ContextLimitSum;
			$this->contextPricePercent = $result->ContextPricePercent;
			$this->autoOptimization = $result->AutoOptimization;
			$this->statusMetricaControl = $result->StatusMetricaControl;
			$this->disabledDomains = $result->DisabledDomains;
			$this->disabledIps = $result->DisabledIps;
			$this->statusOpenStat = $result->StatusOpenStat;
			$this->considerTimeTarget = $result->ConsiderTimeTarget;
			$this->minusKeywords = implode(',', $result->MinusKeywords);
			$this->addRelevantPhrases = $result->AddRelevantPhrases;
			$this->relevantPhrasesBudgetLimit = $result->RelevantPhrasesBudgetLimit;			
		}
	}
}
