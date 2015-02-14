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
	use Illuminate\Database\Schema\Blueprint;

	class Application extends Container
	{
		public function __construct()
		{
			$this->setUpEnvironment();
			$this->setUpDatabase();
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
			$fs = $this['filesystem'];
			$dbPath = $this['path.database'].'/yali.sqlite';
			if(!$fs->exists($dbPath))
			{
				$fs->put($dbPath, '');
			}
			$capsule = new Capsule;
			$capsule->addConnection($this['filesystem']->getRequire($this['path.database'] . '/config.php'));
			$capsule->setAsGlobal();
			$capsule->bootEloquent();
			if(!Capsule::schema()->hasTable('words') || !Capsule::schema()->hasTable('sentences'))
			{
				$this->prepareNewDb();
			}
		}

		protected function prepareNewDb()
		{
			Capsule::schema()->create('words', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('term');
				$table->text('translated');
				$table->index('term', 'idx_words');
			});
			Capsule::schema()->create('sentences', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('term');
				$table->text('translated');
				$table->index('term', 'idx_sentences');
			});
		}

		public static function getArgs()
		{
			$terms = [];
			exec('xsel -o | sed "s/[\"\'<>]//g"', $terms);


			return trim(implode(' ', $terms), " \t\n\r\0\x0B,.-_'\";:!?"); 

		}

		public function translate()
		{
			$term = static::getArgs();

			$words = explode(' ', $term);
			if (count($words) === 1)
			{
				return $this->make('dict')->translate($term);
			}
			else
			{
				if (count($words) > 1 && mb_strlen($term) <= 255)
				{
					return $this->make('trans')->translate($term);
				}
				else
				{
					return $this->make('trans')->translate($term, false);
					//exit(0);
				}
			}
		}


	}

}
