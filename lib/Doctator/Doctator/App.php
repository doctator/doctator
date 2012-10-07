<?php
namespace Doctator\Doctator;

class App extends \Slim\Slim {

	/**
	 * @var \Mongo
	 */
	protected $mongo;

	/**
	 * Initialize the doctator app
	 */
	public function initialize() {
		$this->registerRoutes();
	}

	/**
	 * Connect to MongoDB
	 *
	 * @return \Mongo
	 */
	public function connectDatabase() {
		if ($this->mongo === NULL) {
			$this->mongo = new \Mongo($this->config('mongo.server'), $this->config('mongo.options'));
		}
		return $this->mongo;
	}

	/**
	 * Register routes for this app
	 */
	protected function registerRoutes() {
		$app = $this;
		$app->get('/', function () use ($app) {
			$app->render('home.php');
		})->name('home');

		$app->get('/c/:key/:subject(/:version)', function ($key, $subject, $version = '1.0.0') use ($app) {
			$mongo = $app->connectDatabase();
			$db = $mongo->doctator;
			$collection = $db->comments;

			$comments = $collection->find();

			$app->response()->header('Content-Type', 'application/json');
			$results = array();
			foreach ($comments as $comment) {
				$results[] = json_encode($comment);
			}
			echo json_encode($results);
		})->name('comments-list');

		$app->post('/c/:key/:subject(/:version)', function ($key, $subject, $version = '1.0.0') use ($app) {
			$mongo = $app->connectDatabase();
			$db = $mongo->doctator;
			$collection = $db->comments;

			// TODO Check API key

			$text = $app->request()->post('text');
			if ((string)$text === '') {
				$app->response()->status(400);
				echo json_encode(array(
					'error' => array(
						'text' => 'required'
					)
				));
			}

			$markdown = new \dflydev\markdown\MarkdownParser();

			$parentId = NULL;
			$doc = array(
				'owner' => $key,
				'subject' => $subject,
				'version' => $version,
				'created' => strftime('%FT%T'),
				'parent' => $parentId,
				'author' => array(
					'name' => 'Christopher Hlubek'
				),
				'text' => array(
					'source' => $text,
					'processed' => $markdown->transform($text)
				)
			);
			$collection->insert($doc);

			$app->response()->header('Content-Type', 'application/json');
			echo '';
		})->name('comments-post');

		$app->get('/s/:key/all.js', function ($key) use ($app) {
			$key = (integer)$key;
			$filename = "{$app->config('resources.path')}/$key-all.js";
			if (file_exists($filename)) {
				$app->response()->header('Content-Type', 'application/javascript');
				echo file_get_contents($filename);
			} else {
				// TODO Check api key
				// TODO Generate bundle for api key

				$app->response()->status(404);
				echo "Not found";
			}
		})->name('js-bundle');
	}
}
