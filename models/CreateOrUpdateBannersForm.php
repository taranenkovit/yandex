<?php
namespace backend\modules\models;

use Yii;
use yii\base\Model;

/**
 *	Модель создания компании
 */
class CreateOrUpdateBannersForm extends Model
{	
	//public $result;
	public $regions;
	/* BannerInfo */
	public $banner_id;
	public $campaign_id;
	public $title;
	public $text;
	public $href;
	public $geo;
	/* ContactInfo */
	public $contact_person;
	public $country;
	public $country_code;
	public $city;
	public $street;
	public $house;
	public $build;
	public $apart;
	public $city_code;
	public $phone;
	public $phone_ext;
	public $company_name;
	public $im_client;
	public $im_login;
	public $extra_message;
	public $contact_email;

	public $work_time_day_begin;
	public $work_time_hours_begin;
	public $work_time_minuts_begin;
	public $work_time_day_end;
	public $work_time_hours_end;
	public $work_time_minuts_end;
	public $ogrn;           
	/* MapPoint */
	public $x;
	public $y;
	public $x1;
	public $y1;
	public $x2;
	public $y2;         
	/* BannerPhraseInfo */
	public $old_phrase;
	public $phrase_id;
	public $phrase;
	public $is_rubric;
	public $price;
	public $context_price;
	public $auto_broker;
	public $auto_budget_priority;
	/* PhraseUserParams */
	//public $param1;
	//public $param2;         
	/* Sitelink */
	//public $site_link_title;
	//public $site_link_href;
	//public $minus_keywords;
	
	/**
	 *	Сценарии проверки введенных и полученных данных
	 */
	public function rules()
	{
		return [			
			[
				[	
					/* ContactInfo */
					'contact_person',
					'street',
					'house',
					'build',
					'apart',
					'phone_ext',
					'im_client',
					'im_login',
					'extra_message',
					'contact_email',
					'ogrn',
					/* MapPoint */
					'x',
					'y',
					'x1',
					'y1',
					'x2',
					'y2',
					/* BannerPhraseInfo */
					//'param1',
					//'param2',
					//'minus_keywords',	
					'old_phrase',
					'phrase_id',
					'is_rubric',					
					'contextPrice',
					'auto_broker',
					'auto_budget_priority',
				], 
				'default'
			],
			[
				[					
					'banner_id',
					'campaign_id',
					'title',
					'text',
					'href',
					'geo',
					/* ContactInfo */					
					'country',
					'country_code',
					'city',									
					'city_code',
					'phone',					
					'company_name',					
					'work_time_day_begin',					
					'work_time_hours_begin',
					'work_time_minuts_begin',
					'work_time_day_end',
					'work_time_hours_end',
					'work_time_minuts_end',					
					/* BannerPhraseInfo */					
					'phrase',		
					'price',
					/* PhraseUserParams */
					         
					/* Sitelink */
					//'site_link_title',
					//'site_link_href',
					
				], 
				'required', 'message' =>'Необходимо заполнить это поле!'
			],
			['price', 'double', 'min' => 0.01, 'max' => 84, 
				'message' =>'Данных в этом поле должны быть вещественным числом!',				
				'tooSmall' => 'Минимальное значение 0.01 y.e.!',				
				'tooBig' => 'Максимальное значение 84.0 y.e.!'
			],
			//['email', 'email', 'message' => 'Адрес эл. почты не соответствует формату!'],
		];
	}
	/**
	 *	Получить регионы
	 */
	public function getRegions()
	{		
		$result = YandexAPI4::queryJSON('GetRegions', []);
		$this->regions = $result->data;
	}
		
	/**
	 *	Добавить или обновить компанию
	 */
	public function createOrUpdateBanners()
	{		
		$new_phrase = [
			[
				/* BannerPhraseInfo */
				"PhraseID" => $this->phrase_id,
				"Phrase" => $this->phrase,
				"IsRubric" => $this->is_rubric,
				"Price" => $this->price,
				"ContextPrice" => $this->context_price,
				"AutoBroker" => $this->auto_broker,
				"AutoBudgetPriority" => $this->auto_budget_priority,
				"UserParams" => [
					/* PhraseUserParams */
					"Param1" => null,
					"Param2" => null,
				]	
			]
		];
		
		$old_phrases = unserialize($this->old_phrase);
		
		$param = [
				[
				/* BannerInfo */
				"BannerID" => $this->banner_id,
				"CampaignID" => $this->campaign_id,
				"Title" => $this->title,
				"Text" => $this->text,
				"Href" => $this->href,
				"Geo" => $this->geo,
				"ContactInfo" => [
					/* ContactInfo */
					"ContactPerson" => $this->contact_person,
					"Country" => $this->country,
					"CountryCode" => $this->country_code,
					"City" => $this->city,
					"Street" => $this->street,
					"House" => $this->house,
					"Build" => $this->build,
					"Apart" => $this->apart,
					"CityCode" => $this->city_code,
					"Phone" => $this->phone,
					"PhoneExt" => $this->phone_ext,
					"CompanyName" => $this->company_name,
					"IMClient" => $this->im_client,
					"IMLogin" => $this->im_login,
					"ExtraMessage" => $this->extra_message,
					"ContactEmail" => $this->contact_email,
					"WorkTime" => $this->work_time_day_begin.';'.$this->work_time_day_end.';'.
						$this->work_time_hours_begin.';'.$this->work_time_minuts_begin.';'.
						$this->work_time_hours_end.';'.$this->work_time_minuts_end,
					"OGRN" => $this->ogrn,
					"PointOnMap" => [
						/* MapPoint */
						"x" => $this->x,
						"y" => $this->y,
						"x1" => $this->x1,
						"y1" => $this->y1,
						"x2" => $this->x2,
						"y2" => $this->y2,
					]
				],
				"Phrases" => (is_array($old_phrases))? array_merge($old_phrases, $new_phrase) : $new_phrase,
				"Sitelinks" => null,//[
					//[  
						/* Sitelink */
						//"Title" => null,
						//"Href" => null,
					//],            
				//],
				"MinusKeywords" => []		   
			]
		];
		
		$result = YandexAPI4::queryJSON('CreateOrUpdateBanners', $param);	
		//$this->result = $result;
		return (isset($result->error_detail))? $result->error_detail : null;
	}
		
	/**
	 *	Получить объявление для создания новой фразы
	 *
	 *	@param integer $campaign_id идентификатор кампании
	 *	@param integer $banner_id идентификатор объявления
	 */
	public function getBannerForNewPhrase($campaign_id, $banner_id)
	{		
		$param = [
			//'CampaignIDS' => [$campaign_id],
			'BannerIDS' => [$banner_id],
		];
		$result = YandexAPI4::queryJSON('GetBanners', $param);
		if(isset($result->error_detail))
			return $result->error_detail;
		else
		{			
			if(isset($result->data[0]))
			{
				$result = $result->data[0];
				//$this->result = $result;
				// BannerInfo 
				$this->banner_id = $result->BannerID;
				$this->campaign_id = $result->CampaignID;
				$this->title = $result->Title;
				$this->text = $result->Text;
				$this->href = $result->Href;
				$this->geo = $result->Geo;
				
				// ContactInfo 
				$this->contact_person = $result->ContactInfo->ContactPerson;
				$this->country = $result->ContactInfo->Country;
				$this->country_code = $result->ContactInfo->CountryCode;
				$this->city = $result->ContactInfo->City;
				$this->street = $result->ContactInfo->Street;
				$this->house = $result->ContactInfo->House;
				$this->build = $result->ContactInfo->Build;
				$this->apart = $reult->ContactInfo->Apart;
				$this->city_code = $result->ContactInfo->CityCode;
				$this->phone = $result->ContactInfo->Phone;
				$this->phone_ext = $result->ContactInfo->PhoneExt;
				$this->company_name = $result->ContactInfo->CompanyName;
				$this->im_client = $result->ContactInfo->IMClient;
				$this->im_login = $result->ContactInfo->IMLogin;
				$this->extra_message = $result->ContactInfo->ExtraMessage;
				$this->contact_email = $result->ContactInfo->ContactEmail;			
				
				$work_time = explode(';', $result->ContactInfo->WorkTime);
				$this->work_time_day_begin = $work_time['0'];
				$this->work_time_day_end = $work_time['1'];
				$this->work_time_hours_begin = $work_time['2'];
				$this->work_time_minuts_begin = $work_time['3'];
				$this->work_time_hours_end = $work_time['4'];
				$this->work_time_minuts_end = $work_time['5'];	
					
				$this->ogrn = $result->ContactInfo->OGRN;
				
				// MapPoint 
				$this->x = $result->ContactInfo->PointOnMap->x;
				$this->y = $result->ContactInfo->PointOnMap->y;
				$this->x1 = $result->ContactInfo->PointOnMap->x1;
				$this->y1 = $result->ContactInfo->PointOnMap->y1;
				$this->x2 = $result->ContactInfo->PointOnMap->x2;
				$this->y2 = $result->ContactInfo->PointOnMap->y2;
				
				// BannerPhraseInfo 
				//Сохранение старых фраз				
				$result->Phrases = YandexAPI4::format_array($result->Phrases);
				$this->old_phrase = serialize($result->Phrases);
				//Новая фраза
				$this->phrase_id = 0;
			}
			return null;
		}
	}
	
	/**
	 *	Получить объявление для изменения фразы
	 *
	 *	@param integer $campaign_id идентификатор кампании
	 *	@param integer $banner_id идентификатор объявления
	 *	@param integer $phrase_id идентификатор фразы
	 */
	public function getBannerForEditPhrase($campaign_id, $banner_id, $phrase_id)
	{		
		$param = [
			//'CampaignIDS' => [$campaign_id],
			'BannerIDS' => [$banner_id],			
		];
		$result = YandexAPI4::queryJSON('GetBanners', $param);
		if(isset($result->error_detail))
			return $result->error_detail;
		else
		{			
			if(isset($result->data[0]))
			{
				$result = $result->data[0];
				//$this->result = $result;
				// BannerInfo 
				$this->banner_id = $result->BannerID;
				$this->campaign_id = $result->CampaignID;
				$this->title = $result->Title;
				$this->text = $result->Text;
				$this->href = $result->Href;
				$this->geo = $result->Geo;
				
				// ContactInfo 
				$this->contact_person = $result->ContactInfo->ContactPerson;
				$this->country = $result->ContactInfo->Country;
				$this->country_code = $result->ContactInfo->CountryCode;
				$this->city = $result->ContactInfo->City;
				$this->street = $result->ContactInfo->Street;
				$this->house = $result->ContactInfo->House;
				$this->build = $result->ContactInfo->Build;
				$this->apart = $reult->ContactInfo->Apart;
				$this->city_code = $result->ContactInfo->CityCode;
				$this->phone = $result->ContactInfo->Phone;
				$this->phone_ext = $result->ContactInfo->PhoneExt;
				$this->company_name = $result->ContactInfo->CompanyName;
				$this->im_client = $result->ContactInfo->IMClient;
				$this->im_login = $result->ContactInfo->IMLogin;
				$this->extra_message = $result->ContactInfo->ExtraMessage;
				$this->contact_email = $result->ContactInfo->ContactEmail;			
				
				$work_time = explode(';', $result->ContactInfo->WorkTime);
				$this->work_time_day_begin = $work_time['0'];
				$this->work_time_day_end = $work_time['1'];
				$this->work_time_hours_begin = $work_time['2'];
				$this->work_time_minuts_begin = $work_time['3'];
				$this->work_time_hours_end = $work_time['4'];
				$this->work_time_minuts_end = $work_time['5'];	
					
				$this->ogrn = $result->ContactInfo->OGRN;
				
				// MapPoint 
				$this->x = $result->ContactInfo->PointOnMap->x;
				$this->y = $result->ContactInfo->PointOnMap->y;
				$this->x1 = $result->ContactInfo->PointOnMap->x1;
				$this->y1 = $result->ContactInfo->PointOnMap->y1;
				$this->x2 = $result->ContactInfo->PointOnMap->x2;
				$this->y2 = $result->ContactInfo->PointOnMap->y2;
				
				// BannerPhraseInfo 
				//Сохранение старых фраз				
				$result->Phrases = YandexAPI4::format_array($result->Phrases);
				$phrase = YandexAPI4::getPhrase($result->Phrases, $phrase_id);
				$result->Phrases = YandexAPI4::deletePhrase($result->Phrases, $phrase_id);
				$this->old_phrase = serialize($result->Phrases);
				//Новая фраза
				$this->phrase_id = $phrase['PhraseID'];				
				$this->phrase = $phrase['Phrase'];
				$this->is_rubric = $phrase['IsRubric'];
				$this->price = $phrase['Price'];
				$this->context_price = $phrase['ContextPrice'];
				$this->auto_broker = $phrase['AutoBroker'];
				$this->auto_budget_priority = $phrase['AutoBudgetPriority'];
			}
			return null;
		}
	}
}
