<?php

namespace Yali\Translators
{

	use GuzzleHttp\Client;
	use Yali\Application;
	use Yali\Transformers\TransformerInterface;

	abstract class Base
	{
		/**
		 * Что пытаемся переводить
		 * @var string
		 */
		protected $term;

		/**
		 * @var string Url сервиса который нам вернет перевод.
		 */
		protected $uri = '';

		/**
		 * @var string Яндекс.Словарь требует что-бы в запросе присутствовала эта строка формата "с_какого-на_какой".
		 */
		protected $lang = '';

		/**
		 * Имя модели класса (Пиздец описание конечно, но хз как лаконично это описать).  Переопределить.
		 * @var string
		 */
		protected $modelName = '';

		/**
		 * Тот кто нам будет готовить итоговую строку.
		 * @var TransformerInterface
		 */
		protected $transformer;

		/**
		 * Тот кто будет делать запросы на сервер.
		 * @var Client
		 */
		protected $client;

		public function __construct(Client $client, \Yali\Transformers\Base $transformer)
		{
			$this->client = $client;
			$this->transformer = $transformer;

			// TODO: Айайай конечно, но пусть будет так.
			$this->term = Application::getArgs();
		}

		/**
		 * Описание в базовом классе
		 * @param  $word
		 * @param bool $save
		 * @return string
		 */
		public function translate($word, $save = true)
		{
			$translation = $this->getCache($word);
			if ($translation)
			{
				return $this->transformer->transform($translation->translated);
			}

			$translation = $this->get($word);

			if ($save)
			{
				$this->save($translation);
			}

			return $this->transformer->transform($translation);
		}

		/**
		 * Переводим слово, используя сервис.
		 * @return array
		 */
		protected function get()
		{
			return $this->client->get($this->uri, ['query' => $this->getOptions()])->json();
		}

		/**
		 * Ищем в базе данных запись.
		 * @return null|\Yali\Models\Word
		 */
		protected function getCache()
		{
			return call_user_func([$this->modelName, 'where'], 'term', $this->term)->first();
		}

		/**
		 * Сохранить перевод в каше.
		 * @param $translation
		 */
		protected function save($translation)
		{
			$model = new $this->modelName;
			$model->term = $this->term;
			$model->translated = json_encode($translation);
			$model->save();
		}


		/**
		 * Возращает массив. Этот массив будет использоваться для постоения query string.
		 *
		 * @return array
		 */
		abstract protected function getOptions();

	}

}
