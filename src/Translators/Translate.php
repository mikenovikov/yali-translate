<?php

namespace Yali\Translators
{

	class Translate extends Base
	{
		protected $uri = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
		protected $lang = 'ru';
		protected $modelName = 'Yali\Models\Sentence';

		protected function getOptions()
		{
			return [
				'key'  => getenv('TRANS_KEY'),
				'lang' => $this->lang,
				'text' => $this->term
			];
		}

	}

}
