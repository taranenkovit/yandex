<?php
namespace backend\modules\models;

use Yii;
use yii\base\Model;

/**
 *	Модель создания сервиса Yandex
 */
class CreateServiceForm extends Model
{
	//Логин на яндексе
	public $login;//taranenkovit1983
	// Идентификатор приложения
	public $client_id;// = '10bdfbe979bc4d7dab0011bb7c905ddd'; 
	// Пароль приложения
	public $client_secret;// = 'd145359e83ca49e3831426bad5844dd2';
	//Токен Яндекс
	public $token;	
	
	/**
	 *	Сценарии проверки введенных и полученных данных
	 */
	public function rules()
	{
		return [			
			[['login','client_id','client_secret'], 'required', 'message' =>'Необходимо заполнить это поле!'],
		];
	}
		
	/**
	 * 	Добавить информацию о веб-сервисе в БД
	 *
	 *	@return boolean результат обращения к БД
	 */
	public function addService()
	{
		$sql = "INSERT INTO yandex_direct(login, client_id, client_secret, token)
				VALUES (:login, :client_id, :client_secret, :token)";
					
		$result = Yii::$app->db->createCommand($sql)
				->bindValue(':login',$this->login)
				->bindValue(':client_id',$this->client_id)
				->bindValue(':client_secret',$this->client_secret)
				->bindValue(':token','')
				->execute();
		return $result;		
	}		
}
