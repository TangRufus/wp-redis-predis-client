<?php

class WPPredisDecoratorTest extends PHPUnit_Framework_TestCase {

	protected static $arguments = array(
		'host' => '127.0.0.1',
		'port' => 6379,
	);

	protected static $options = array();

	public function setUp() {
		parent::setUp();
		$client = new Predis\Client( self::$arguments, self::$options );
		$this->client = new WP_Predis\Decorator( $client );
	}

	public function test_is_connected() {
		$this->client->connect();
		$this->assertTrue( $this->client->isConnected() );
	}

	public function test_hexists() {
		$key = 'test';
		$field = 'foo';

		$exists = $this->client->hexists( $key, $field );
		$this->assertFalse( $exists );
	}

	public function test_exists() {
		$key = 'test';

		$exists = $this->client->exists( $key );
		$this->assertFalse( $exists );
	}

	public function test_get() {
		$key = 'test';

		$value = $this->client->get( $key );
		$this->assertFalse( $value );
	}

	public function test_hget() {
		$key = 'test';
		$field = 'foo';

		$value = $this->client->hget( $key, $field );
		$this->assertFalse( $value );
	}

	public function test_setex() {
		$key = 'foo';
		$value = 'bar';

		$result = $this->client->setex( $key, 100, $value );
		$this->assertTrue( $result );
	}

	public function test_set() {
		$key = 'foo';
		$value = 'bar';

		$result = $this->client->set( $key, $value );
		$this->assertTrue( $result );
	}

	public function test_delete() {
		$this->client->mset( array(
			'foo' => 'bar',
			'bar' => 'baz',
			'baz' => 'qux',
		));

		$deleted = $this->client->del( 'foo', 'bar', 'baz' );
		$this->assertEquals( 3, $deleted );
	}

	public function test_hdelete() {
		$key = 'test';
		$fields = array(
			'foo',
			'bar',
			'baz',
		);

		$this->client->hmset( $key, array(
			'foo' => 'bar',
			'bar' => 'baz',
			'baz' => 'qux',
		));

		$deleted = $this->client->hdel( $key, 'foo', 'bar', 'baz' );
		$this->assertEquals( 3, $deleted );
	}

	public function tearDown() {
		parent::tearDown();
		$this->client->flushall();
	}
}
