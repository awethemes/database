<?php

use Awethemes\Database\Database;
use Awethemes\Database\Connection;

class Connection_Test extends WP_UnitTestCase {
	/* @var \Awethemes\Database\Connection */
	protected $connection;

	public function setUp() {
		parent::setUp();

		global $wpdb;
		$this->connection = new Connection($wpdb);
	}

	public function testTable() {
		$builder = $this->connection->table( 'posts' );
		$this->assertInstanceOf( \Awethemes\Database\Builder::class, $builder );
		$this->assertInstanceOf( \Awethemes\Database\Grammar::class, $builder->getGrammar() );
	}

	public function testSelect() {
		global $wpdb;

		$builder = $this->getBuilder();
		$builder->select( '*' )->from( 'users' );
		$this->assertEquals( "select * from `{$wpdb->users}`", $builder->toSql() );

		$builder = $this->getBuilder();
		$builder->select( '*' )->from( 'posts' )->where( 'post_type', '=', 'post' );
		$this->assertEquals( "select * from `{$wpdb->posts}` where `post_type` = %s", $builder->toSql() );
	}

	public function testGetItems() {
		$this->factory->post->create_many( 5 );

		$builder = $this->getBuilder();
		$posts   = $builder->select( '*' )->from( 'posts' )->get();
		$this->assertInternalType( 'array', $posts );
		$this->assertCount( 5, $posts );
	}

	public function testRetriveItem() {
		$this->factory->post->create_many( 5 );

		$post = $this->getBuilder()->select( '*' )->from( 'posts' )->first();
		$this->assertInternalType( 'array', $post );
		$this->assertArrayHasKey( 'ID', $post );
	}

	/**
	 * @expectedException \Awethemes\Database\QueryException
	 */
	public function testQueryException() {
		$this->getBuilder()->select( '*' )->from( 'not_found' )->get();
	}

	public function testUseDatabase() {
		global $wpdb;
		$builder = Database::newQuery()->select( '*' )->from( 'posts' );
		$this->assertEquals( "select * from `{$wpdb->posts}`", $builder->toSql() );
	}

	/**
	 * @return \Awethemes\Database\Builder
	 */
	private function getBuilder() {
		return $this->connection->newQuery();
	}
}
