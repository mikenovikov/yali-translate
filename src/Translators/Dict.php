<?php

namespace Yali\Translators
{

	class Dict extends Base
	{
		protected $uri = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup';
		protected $lang = 'en-ru';
		protected $modelName = 'Yali\Models\Word';

		protected function getOptions()
		{
			return [
				'key'  => getenv('DICT_KEY'),
				'lang' => $this->lang,
				'text' => $this->term,
				'flags' => 4
			];
		}
	}

}
