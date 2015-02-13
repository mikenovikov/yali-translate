<?php

namespace Yali
{
	use Illuminate\Config\EnvironmentVariables;
	use Illuminate\Config\FileEnvironmentVariablesLoader;
	use Illuminate\Container\Container;
	use Illuminate\Database\Capsule\Manager as Capsule;
	use Illuminate\Filesystem\Filesystem;
	use Yali\Transformers\YandexDictResponse;
	use Yali\Transformers\YandexTranslateResponse;
	use Yali\Translators\Dict;
	use Yali\Translators\Translate;

	class Application extends Container
	{
		public function __construct()
		{
			$this->setUpEnvironment();
			$this->setUpDatabase();
			$this->translate();
		}

		private function setUpEnvironment()
		{
			$this['path.database'] = getenv('HOME') . '/.yali/database';
			$this['path.base'] = getenv('HOME') . '/.yali';
			$this->singleton('filesystem', 'Illuminate\Filesystem\Filesystem');
			$this->bind('client', 'GuzzleHttp\Client');
			$this->bindShared('dict', function ($c)
			{
				return new Dict($c['client'], new YandexDictResponse());
			});
			$this->bindShared('trans', function ($c)
			{
				return new Translate($c['client'], new YandexTranslateResponse());
			});
			$config = new EnvironmentVariables(new FileEnvironmentVariablesLoader(new Filesystem(), $this['path.base']));
			$config->load();
		}

		private function setUpDatabase()
		{
			$capsule = new Capsule;
			$capsule->addConnection($this['filesystem']->getRequire($this['path.database'] . '/config.php'));
			$capsule->setAsGlobal();
			$capsule->bootEloquent();
		}

		public static function getArgs()
		{
			$argv = $_SERVER['argv'];
			return isset($argv[1]) && !empty($argv[1]) ? trim($argv[1], " \t\n\r\0\x0B,.-_'\";:!?") : exit('No word for translation');
		}

		private function translate()
		{
			$term = static::getArgs();

			$words = explode(' ', $term);
			if (count($words) === 1)
			{
				echo $this->make('dict')->translate($term);
			}
			else
			{
				if (count($words) > 1 && mb_strlen($term) <= 255)
				{
					echo $this->make('trans')->translate($term);
				}
				else
				{
					echo $this->make('trans')->translate($term, false);
					exit(0);
				}
			}
		}


	}

}
