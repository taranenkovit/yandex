<?php

namespace backend\modules\models;

use Yii;
use yii\base\Model;

/**
 *	Модель использования Yandex
 */
class YandexAPI4 extends Model
{
	//Логин на яндексе
	public static $login;
	// Идентификатор приложения
	public static $client_id;// = '10bdfbe979bc4d7dab0011bb7c905ddd'; 
	// Пароль приложения
	public static $client_secret;// = 'd145359e83ca49e3831426bad5844dd2';
	//Токен Яндекс
	public static $token;
	
	public $result;	
	/**
	 *	Конструктор класса
	 */
	public function __construct($client_id = null)
	{
		parent::__construct();
		//Считываем с сессии client_id для получения токина с сервера
		if($client_id === null)
		{			
			$session = Yii::$app->session;
			if ($session->isActive)
			{
				$session->open();
			}
			$client_id = $session->get('client_id');
			$session->remove('client_id');
			$session->close();
		}
		//Личные данные о пользователе
		if($client_id !== null)
		{
			$result = $this->findService($client_id);
			if($result)
			{
				self::$login = $result['login'];
				self::$client_id = $result['client_id'];
				self::$client_secret = $result['client_secret'];
				self::$token = $result['token'];				
			}	
		}
	}
	
	/**
	 *	Идентификация пользователя
	 */
	private function findService($client_id)
	{
		//Ищем информацию о веб-сервисе в БД
		$sql = "SELECT * FROM yandex_direct WHERE client_id=:client_id";
					
		return  Yii::$app->db->createCommand($sql)
				->bindValue(':client_id', $client_id)
				->queryOne();		
	}
	
	/**
	 *	Получить токен и сохранить в БД
	 *
	 *	@return string информацию о токене
	 */
	public function getToken()
	{
		// Если скрипт был вызван с указанием параметра "code" в URL,
		// то выполняется запрос на получение токена
		if (Yii::$app->request->get('code'))
		{			
			// Формирование параметров (тела) POST-запроса с указанием кода подтверждения
			$query = [
				'grant_type' => 'authorization_code',
				'code' => Yii::$app->request->get('code'),
				'client_id' => self::$client_id,
				'client_secret' => self::$client_secret,
			];
			
			$query = http_build_query($query);

			// Формирование заголовков POST-запроса
			$header = "Content-type: application/x-www-form-urlencoded";

			// Выполнение POST-запроса и вывод результата
			$opts = ['http' =>
				[
					'method'  => 'POST',
					'header'  => $header,
					'content' => $query
				] 
			];
			
			$context = stream_context_create($opts);
			$result = file_get_contents('https://oauth.yandex.ru/token', false, $context);
			$result = json_decode($result);
			return $result->access_token;
		}
	}
	
	/**
	 *	Сохранить токен в БД
	 */	
	public function saveToken($access_token)
	{
		$sql = "UPDATE yandex_direct
					SET								
						token=:token
					WHERE login=:login AND client_id=:client_id AND client_secret=:client_secret";
					
		$result = Yii::$app->db->createCommand($sql)
				->bindValue(':login',YandexAPI4::$login)
				->bindValue(':client_id',YandexAPI4::$client_id)
				->bindValue(':client_secret',YandexAPI4::$client_secret)
				->bindValue(':token',$access_token)
				->execute();	
	}
	
	/**
	 *	Запросы к API в формате JSON
	 *
	 *	@param array $method метод запроса
	 *	@param array $param параметры запроса
	 *	@return array ответ
	 */
	public static function queryJSON($method, $param)
	{		
		// Формирование параметров (тела) POST-запроса с указанием кода подтверждения		
		$query = [				
			'method' => $method,
			'param' => self::format_utf_8($param),
			'locale' => 'ru',
            'login' => self::$login,
            'application_id' => self::$client_id,
            'token' => self::$token		
		];
		
		$query = json_encode($query);
				
		// Формирование заголовков POST-запроса
		$header = "Content-type: application/json; charset=utf-8";

		// Выполнение POST-запроса и вывод результата
		$opts = ['http' =>
			[
				'method'  => 'POST',
				'header'  => $header,
				'content' => $query,				
			] 
		];
			
		$context = stream_context_create($opts);
		$result = file_get_contents('https://api-sandbox.direct.yandex.ru/v4/json/', false, $context);
		$result = json_decode($result);		
		
		return $result;		
	}
	

	/*public static function queryJSONcontrol($method, $param)
	{		
		// Формирование параметров (тела) POST-запроса с указанием кода подтверждения		
		$query = [				
			'method' => $method,
			'param' => self::format_utf_8($param),
			'locale' => 'ru',
            'login' => self::$login,
            'application_id' => self::$client_id,
            'token' => self::$token		
		];
		
		$query = json_encode($query);			
			
		return $query;		
	}*/
	
	/**
	 * 	Перекодировка в UTF-8 всех элементов массива
	 *
	 *	@param mixed $element элемент массива
	 *	@return mixed масив элементы, которого типа string в формате utf-8
	 */
	public function format_utf_8($element)
	{		
		if(is_array($element)){
			foreach($element as $key => $value)
				$element[$key] = self::format_utf_8($value);			
		}
		else {
			if(is_string($element))
				$element = utf8_encode($element);
		}
		return $element;
	}
	
	/**
	 *	Преобразование массива с объектами в массив массивов
	 *
	 *	@param mixed $element элемент массива со структурой объектами
	 *	@return mixed масив элементы, которого не имеют структуры объектов
	 */
	public static function format_array($element)
	{
		if(is_array($element) or is_object($element)){
			if(is_object($element))
				$element = (array)$element;
			foreach($element as $key => $value)			
				$element[$key] = self::format_array($value);			
		}		
		return $element;
	}
	
	/**
	 *	Выделение нужной фразы
	 *	
	 *	@param mixed $phrases массив враз
	 *	@param integer $phrase_id идентификатор нужной фразы
	 *	@return mixed нужная фраза
	 */
	public static function getPhrase($phrases, $phrase_id)
	{
		foreach($phrases as $phrase)
		{
			if($phrase['PhraseID'] == $phrase_id)
				return $phrase;
		}
		return null;
	}
	
	/**
	 *	Удаление нужной фразы
	 *	
	 *	@param mixed $phrases массив враз
	 *	@param integer $phrase_id идентификатор удаляемой фразы
	 *	@return mixed массив враз без удаленной фразы
	 */
	public static function deletePhrase($phrases, $phrase_id)
	{
		foreach($phrases as $key => $phrase)
		{
			if($phrase['PhraseID'] == $phrase_id)
				unset($phrases[$key]);
		}
		//Переиндексация значений массива
		return array_values($phrases);
	}
}
