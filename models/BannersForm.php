<?php

namespace backend\modules\models;

use Yii;
use yii\base\Model;
//use yii\db\ActiveRecord;
//use yii\data\Pagination;
use backend\modules\models\YandexAPI4;

/**
 *	Модель представления Ваших компаний (только клиенты не агентства!)
 */
class BannersForm extends Model
{	  
	//Список компаний
	public $list;
	//public $result;
		
	/**
	 *	Получить все объявления кампании
	 *
	 *	@param integer $campaign_id идентификатор кампании
	 */
	public function getBannersList($campaign_id)
	{		
		$param = ['CampaignIDS' => [$campaign_id]];
		$result = YandexAPI4::queryJSON('GetBanners', $param);
		if(isset($result->error_detail))
			return $result->error_detail;
		else
		{
			$this->list = $result->data;
			return null;
		}
	}

	/**
	 *	Удалить фразу из объявления
	 *	 
	 *	@param integer $campaign_id идентификатор кампании
	 *	@param integer $banner_id идентификатор объявления
	 *	@param integer $phrase_id идентификатор фразы
	 */
	public function deletePhrase($campaign_id, $banner_id, $phrase_id)
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
				//Преобразуем классы в массивы
				$param = YandexAPI4::format_array($result->data);
				//Удаляем нужную фразу
				$param[0]['Phrases'] = YandexAPI4::deletePhrase($param[0]['Phrases'], $phrase_id);
				
				$result = YandexAPI4::queryJSON('CreateOrUpdateBanners', $param);					
				return (isset($result->error_detail))? $result->error_detail : null;				
			}			
		}
		return null;
	}	
}
