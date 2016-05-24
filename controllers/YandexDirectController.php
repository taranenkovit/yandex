<?php

namespace backend\modules\controllers;

use Yii;
use yii\web\Controller;

use backend\modules\models\YandexAPI4;
use backend\modules\models\ServicesForm;
use backend\modules\models\CreateServiceForm;
use backend\modules\models\CampaignsForm;
use backend\modules\models\CreateOrUpdateCampaignForm;
use backend\modules\models\BannersForm;
use backend\modules\models\CreateOrUpdateBannersForm;

class YandexDirectController extends Controller
{
	//public $layout = 'main';

	/**	 
	 *	Представить информацию о сервисах YandexDirect
	 */
	public function actionShowServices()
	{		
		$model_services = new ServicesForm();
		
		return $this->render('services', [						
			'model_services' => $model_services,
		]);		
	}	
	
	/**
	 *	Сохранить информацию об новом сервисе в БД
	 */
	public function actionCreateService()
	{		
		$model_create_service = new CreateServiceForm();
		
		if (Yii::$app->request->isPost) {
			if($model_create_service->load(Yii::$app->request->post()))
			{
				$model_create_service->addService();
				return $this->redirect(['/yandex/yandex-direct/show-services']);
			}
		}
		
		return $this->render('create-service', [						
			'model_create_service' => $model_create_service,			
		]);
	}
	
	/**
	 *	Запрос на авторизацию 
	 *
	 *	@param integer $client_id идентификатор клиента
	 */
	public function actionResponseToken($client_id)
	{		
		//Сохраняем в сессию client_id
		$session = Yii::$app->session;
		if ($session->isActive)
		{
			$session->open();
		}
		$session->set('client_id', $client_id);
		$session->close();
		
		Yii::$app->getResponse()
			->redirect("https://oauth.yandex.ru/authorize?response_type=code&client_id=".$client_id)
			->send();
	}
	
	/**
	 *	Получение токена и его сохранение в БД
	 */
	public function actionGetToken()
	{		
		$model_api = new YandexAPI4();				
		
		//Получаем и сохраняем токен
		$model_api->saveToken($model_api->getToken());		
		
		return $this->redirect(['/yandex/yandex-direct/show-services']);				
	}
	
	/**
	 *	Показать компании
	 *
	 *	@param integer $client_id идентификатор клиента
	 */
	public function actionCampaigns($client_id = null)
	{
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$model_campaigns->getCampaignsList();			
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,			
		]);
	}
	/**
	 *	Показать компании
	 *
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionCreateOrUpdateCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_couc = new CreateOrUpdateCampaignForm();		
		
		$model_couc->getTimeZones();
		
		if($campaignID != 0)
			$model_couc->getCampaignsParams($campaignID);
			
		if (Yii::$app->request->isPost) {			
			
			if($model_couc->load(Yii::$app->request->post()))
			{
				$result = $model_couc->createOrUpdateCampaign();
				if($result->error_detail !== null)
				{
					return $this->render('create-or-update-campaign', [			
						'model_couc' => $model_couc,			
						'campaignID' => $campaignID,
						'massage_error' => $result->error_detail,
					]);
				}
				else
					return $this->redirect(['/yandex/yandex-direct/show-services']);
			}
		}
		
		return $this->render('create-or-update-campaign', [			
			'model_couc' => $model_couc,			
			'campaignID' => $campaignID,			
		]);
	}
	
	/**
	 *	Разрешить показывать компанию
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionResumeCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$massage_error = $model_campaigns->resumeCampaign($campaignID);
		
		$model_campaigns->getCampaignsList();
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,	
			'massage_error' => $massage_error,
		]);
	}
	
	/**
	 *	Остановить компанию
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionStopCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$massage_error = $model_campaigns->stopCampaign($campaignID);
		
		$model_campaigns->getCampaignsList();
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,	
			'massage_error' => $massage_error,
		]);
	}
	
	/**
	 *	Поместить компанию в архив
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionArchiveCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$massage_error = $model_campaigns->archiveCampaign($campaignID);
		
		$model_campaigns->getCampaignsList();
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,			
			'massage_error' => $massage_error,
		]);
	}
	
	/**
	 *	Извлечь кампанию из архива
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionUnArchiveCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$massage_error = $model_campaigns->unArchiveCampaign($campaignID);
		
		$model_campaigns->getCampaignsList();
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,	
			'massage_error' => $massage_error,
		]);
	}
	
	/**
	 *	Удалить кампанию
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaignID идентификатор кампании
	 */
	public function actionDeleteCampaign($client_id = null, $campaignID = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_campaigns = new CampaignsForm();
		
		$massage_error = $model_campaigns->deleteCampaign($campaignID);
		
		$model_campaigns->getCampaignsList();
		
		return $this->render('campaigns', [			
			'model_campaigns' => $model_campaigns,
			'massage_error' => $massage_error,
		]);
	}
	
	/**
	 *	Просмотреть все объявления компании
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaign_id идентификатор кампании
	 */
	public function actionBanners($client_id = null, $campaign_id = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_banners = new BannersForm();
		
		$massage_error = $model_banners->getBannersList($campaign_id);		
		
		return $this->render('banners', [			
			'model_banners' => $model_banners,
			'massage_error' => $massage_error,			
			'campaign_id' => $campaign_id,
		]);
	}
	
	/**
	 *	Создать/изменить объявление компании
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaign_id идентификатор кампании
	 *	@param integer $banner_id идентификатор объявления
	 *	@param integer $phrase_id идентификатор фразы
	 */
	public function actionCreateOrUpdateBanners($client_id = null, $campaign_id = 0, $banner_id = 0, $phrase_id = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_coub = new CreateOrUpdateBannersForm();
		
		//Получить список регионов
		$model_coub->getRegions();

		if($banner_id != 0 and $phrase_id == 0)
			$model_coub->getBannerForNewPhrase($campaign_id, $banner_id);
		
		if($banner_id != 0 and $phrase_id != 0)
			$model_coub->getBannerForEditPhrase($campaign_id, $banner_id, $phrase_id);
		
		if (Yii::$app->request->isPost) {			
			
			if($model_coub->load(Yii::$app->request->post()))
			{
				$massage_error = $model_coub->createOrUpdateBanners();				
				if(!isset($massage_error))
				{
					return $this->redirect([
						'/yandex/yandex-direct/banners', 
						'client_id' => $client_id, 
						'campaign_id' => $campaign_id						
					]);
				}
			}
		}
				
		return $this->render('create-or-update-banners', [			
			'model_coub' => $model_coub,
			'massage_error' => $massage_error,			
			'campaign_id' => $campaign_id,
			'banner_id' => $banner_id,
			'phrase_id' => $phrase_id,
		]);
	}
	
	/**
	 *	Удалить фразу
	 *	 
	 *	@param integer $client_id идентификатор клиента
	 *	@param integer $campaign_id идентификатор кампании
	 *	@param integer $banner_id идентификатор объявления
	 *	@param integer $phrase_id идентификатор фразы
	 */
	public function actionDeletePhrase($client_id = null, $campaign_id = 0, $banner_id = 0, $phrase_id = 0)
	{	
		$model_api = new YandexAPI4($client_id);
		
		$model_banners = new BannersForm();
		
		$massage_error = $model_banners->deletePhrase($campaign_id, $banner_id, $phrase_id);
		
		$massage_error = $model_banners->getBannersList($campaign_id);
				
		return $this->render('banners', [			
			'model_banners' => $model_banners,
			'massage_error' => $massage_error,			
			'campaign_id' => $campaign_id,
		]);
	}

}
