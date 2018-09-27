<?php
namespace Awethemes\Database;

class Builder extends \Database\Query\Builder {
	/**
	 * Execute the query as a "select" statement.
	 *
	 * @param  array $columns
	 * @return array
	 */
	public function get( $columns = [ '*' ] ) {
		return $this->onceWithColumns( $columns, function () {
			return $this->connection->fetchAll( $this->toSql(), $this->getBindings() );
		} );
	}

	/**
	 * Execute the given callback while selecting the given columns.
	 *
	 * After running the callback, the columns are reset to the original value.
	 *
	 * @param  array    $columns
	 * @param  callable $callback
	 *
	 * @return mixed
	 */
	protected function onceWithColumns( $columns, $callback ) {
		$original = $this->columns;

		if ( is_null( $original ) ) {
			$this->columns = $columns;
		}

		$result = $callback();

		$this->columns = $original;

		return $result;
	}
}
