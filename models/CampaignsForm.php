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
class CampaignsForm extends Model
{	  
	//Список компаний
	public $list;	
	
	/**
	 *	Получить список компаний
	 */
	public function getCampaignsList()
	{		
		$this->list = YandexAPI4::queryJSON('GetCampaignsList', [YandexAPI4::$login]);
	}
	/**
	 *	Разрешить показывать компанию
	 *
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function resumeCampaign($campaignID = 0)
	{
		if($campaignID != 0)
		{
			$param = ["CampaignID" => $campaignID];
			$result = YandexAPI4::queryJSON('ResumeCampaign', $param);
			return (isset($result->error_detail))? $result->error_detail : null;
		}
	}
	
	/**
	 *	Остановить компанию
	 *
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function stopCampaign($campaignID = 0)
	{
		if($campaignID != 0)
		{
			$param = ["CampaignID" => $campaignID];
			$result = YandexAPI4::queryJSON('StopCampaign', $param);
			return (isset($result->error_detail))? $result->error_detail : null;
		}
	}
	
	/**
	 *	Поместить компанию в архив
	 *
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function archiveCampaign($campaignID = 0)
	{
		if($campaignID != 0)
		{
			$param = ["CampaignID" => $campaignID];
			$result = YandexAPI4::queryJSON('ArchiveCampaign', $param);
			return (isset($result->error_detail))? $result->error_detail : null;
		}
	}
	
	/**
	 *	Извлечь кампанию из архива
	 *
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function unArchiveCampaign($campaignID = 0)
	{
		if($campaignID != 0)
		{
			$param = ["CampaignID" => $campaignID];
			$result = YandexAPI4::queryJSON('UnArchiveCampaign', $param);
			return (isset($result->error_detail))? $result->error_detail : null;
		}
	}
	
	/**
	 *	Удалить кампанию
	 *
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function deleteCampaign($campaignID = 0)
	{
		if($campaignID != 0)
		{
			$param = ["CampaignID" => $campaignID];
			$result = YandexAPI4::queryJSON('DeleteCampaign', $param);			
			return (isset($result->error_detail))? $result->error_detail : null;
		}
	}
}
