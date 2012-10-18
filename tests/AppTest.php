<?php

class AppTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		//Remove environment mode if set
		unset($_ENV['SLIM_MODE']);

		//Reset session
		$_SESSION = array();

		//Prepare default environment variables
		\Slim\Environment::mock(array(
			'SCRIPT_NAME' => '/index.php', //<-- Physical
			'PATH_INFO' => '/', //<-- Virtual
			'QUERY_STRING' => '',
			'SERVER_NAME' => 'doctator.org',
		));
	}

	/**
	 * @test
	 */
	public function homepageRendersTemplate() {
		$app = new \Doctator\Doctator\App();
		$app->initialize();

		$env = $app->environment();
		$env['PATH_INFO'] = '/';

		$app->call();
		list($status, $header, $body) = $app->response()->finalize();
		$this->assertEquals(200, $status);
		$this->assertSelectEquals('h1', 'Improve your docs', true, $app->response()->body());
	}
}