<?php

namespace Yali\Transformers
{

	class YandexDictResponse extends Base
	{
		/**
		 * @var array Яндекс.Словаь возращает имена честей речи на буржуском языке.
		 */
		protected $partsOfSpeech = [
			'noun'        => 'сущ',
			'adjective'   => 'прил',
			'verb'        => 'гл',
			'participle'  => 'прич',
			'adverb'      => 'нареч',
			'pronoun'     => 'местоим',
			'numeral'     => 'числ',
			'preposition' => 'предл',
			'particle'    => 'част'
		];

		public function transform($term)
		{

			// TODO: Guzzle возращает массив, массив мне не нужен. Почитать доки Газла. "Пока педаль".
			if (is_array($term)) $term = json_decode(json_encode($term));
			else $term = json_decode($term);

			if (empty($term->def))
			{
				return 'No Translation';
			}
			$result = '';

			$result .= $term->def[0]->text . ' [' . $term->def[0]->ts . ']' . PHP_EOL;
			foreach ($term->def as $one)
			{
				foreach ($one->tr as $tr)
				{
					$one->pos = $this->translatePartOfSpeech($one->pos);
					$result .= "$one->pos. => $tr->text";
					if (isset($tr->syn))
					{
						foreach ($tr->syn as $sy)
						{
							$result .= ", $sy->text";
						}
						$result .= PHP_EOL;
					}
					else
					{
						$result .= PHP_EOL;
					}
					if (isset($tr->mean))
					{
						$result .= "\t(";
						$first = true;
						foreach ($tr->mean as $mn)
						{
							if ($first)
							{
								$result .= $mn->text;
								$first = false;
							}
							else
							{
								$result .= ", $mn->text";
							}
						}
						$result .= ")" . PHP_EOL;
					}
					if (isset($tr->ex))
					{
						$counter = 1;
						foreach ($tr->ex as $expl)
						{
							if ($counter <= 1)
							{
								$result .= "\t\t$expl->text - " . $expl->tr[0]->text . PHP_EOL;
								$counter++;
							}
							else
							{
								continue;
							}
						}
					}
				}
			}

			return $this->getCountString($result);
		}


		/**
		 * Яндекс.Словаь возвращает части речи на английском языке. Можно переводить,
		 * можно и нет. Метод на всякий случай.
		 *
		 * @param $part
		 * @return mixed
		 */
		protected function translatePartOfSpeech($part)
		{
			if (array_key_exists($part, $this->partsOfSpeech))
			{
				return $this->partsOfSpeech[$part];
			}
			return $part;
		}

	}

}
