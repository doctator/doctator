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
				$results[] = array(
					'id' => (string)$comment['_id'],
					'owner' => $comment['owner'],
					'subject' => $comment['subject'],
					'version' => $comment['version'],
					'created' => $comment['created'],
					'parent' => $comment['parent'],
					'author' => $comment['author'],
					'processed' => $comment['text']['processed'],
				);
			}
			echo json_encode($results);
		})->name('comments-list');

		$app->post('/c/:key/:subject(/:version)', function ($key, $subject, $version = '1.0.0') use ($app) {
			$mongo = $app->connectDatabase();
			$db = $mongo->doctator;

			$request = $app->request();
			$response = $app->response();

			// TODO Check API key

			$collection = $db->comments;

			$text = $request->post('text');
			if ((string)$text === '') {
				$response->status(400);
				echo json_encode(array(
					'error' => array(
						'text' => 'required'
					)
				));
				return;
			}

			$markdown = new \dflydev\markdown\MarkdownParser();

			if ($request->post('parent')) {
				$parentId = new \MongoId($request->post('parent'));
				$parent = $collection->findOne(array('_id' => $parentId));
				if ($parent === NULL) {
					$response->status(400);
					echo json_encode(array(
						'error' => array(
							'parent' => 'invalid'
						)
					));
					return;
				}
				$parentRef = \MongoDBRef::create('comments', $parent['_id']);
			} else {
				$parentRef = NULL;
			}
			$doc = array(
				'owner' => $key,
				'subject' => $subject,
				'version' => $version,
				'created' => strftime('%FT%T'),
				'parent' => $parentRef,
				'author' => array(
					// TODO Use submitted user information
					'name' => 'Christopher Hlubek'
				),
				'text' => array(
					'source' => $text,
					'processed' => $markdown->transform($text)
				)
			);
			$collection->insert($doc);

			$response->status(201);
			$response->header('Content-Type', 'application/json');
			echo json_encode(array(
				'id' => (string)$doc['_id']
			));
		})->name('comments-post');

		$app->get('/s/:key/all.js', function ($key) use ($app) {
			// TODO Move to build system and compile static file
			$app->response()->header('Content-Type', 'application/javascript');
			$app->render('all.js');
		})->name('js-bundle');
	}
}
