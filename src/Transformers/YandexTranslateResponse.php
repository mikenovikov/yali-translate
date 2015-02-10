<?php

namespace Yali\Transformers
{

	class YandexTranslateResponse extends Base
	{

		public function transform($term)
		{
			if (is_array($term))
			{
				$term = json_decode(json_encode($term));
			}
			else
			{
				$term = json_decode($term);
			}

			return $this->getCountString($term->text[0]);
		}
	}

}
