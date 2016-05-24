<?php

namespace backend\modules\models;

use Yii;
use yii\base\Model;

/**
 *	Модель представления сервисов Yandex
 */
class ServicesForm extends Model
{	
	//Ваши сервисы
	public $services;	

	/**
	 *	Конструктор
	 */
	public function __construct()
	{
		parent::__construct();
		//Получаем имеющиеся сервисы
		$this->services = $this->showWebServices();
	}
	
	/**
	 *	Просмотр информации о веб-сервиах в БД
	 */
	private function showWebServices()
	{
		$sql = "SELECT * FROM yandex_direct";
					
		return Yii::$app->db->createCommand($sql)				
				->queryAll();		
	}	
}
