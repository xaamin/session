<?php 
namespace Xaamin\Session\Contracts;

interface SessionInterface 
{	
	/**
	 * Check if the sessión exists in the store
	 *
	 * @param  mixed   $index
	 * @return boolean
	 */
	public function has($index);

	/**
	 * Put a value in the session store.
	 *
	 * @param  mixed   $index Session index
	 * @param  mixed   $value
	 * @return void
	 */
	public function put($index, $value = null);

	/**
	 * Flash a value in the session store for next request.
	 *
	 * @param  mixed   $index Session index
	 * @param  mixed   $value
	 * @return void
	 */
	public function flash($index, $value = null);

	/**
	 * Gets the value in the session.
	 * If index is null returns a default specified.
	 *
	 * @param  string   $index
	 * @param  mixed 	$default
	 * @return mixed
	 */
	public function get($index, $default = null);

	/**
	 * Gets the session value and remove it from the store.
	 * If index is null returns a default specified.
	 *
	 * @param  string   $index
	 * @param  mixed 	$default
	 * @return mixed
	 */
	public function pull($index, $default = null);

	/**
	 * Get all stored session values.
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Removes session from the store.
	 *
	 * @return void
	 */
	public function forget($index);

	/**
	 * Removes all session from the store
	 * @return type
	 */
	public function flush();
}