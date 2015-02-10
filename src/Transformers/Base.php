<?php

namespace Yali\Transformers
{

	abstract class Base
	{
		/**
		 * notify-osd Позволяет выводить только 10 строк. Так что пока такая вот педаль.
		 * @param $str
		 * @param int $num
		 * @return string
		 */
		public function getCountString($str, $num = 10)
		{
			$str = explode("\n", $str);
			$result = array_slice($str, 0, $num);
			return implode("\n", $result);
		}

		/**
		 * Обработать $what, и вернуть строку.
		 * @param $what
		 * @return string
		 */
		abstract public function transform($what);
	}

}
