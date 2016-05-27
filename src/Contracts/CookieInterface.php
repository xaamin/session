<?php
namespace Xaamin\Session\Contracts;

interface CookieInterface
{
    /**
     * Check if the cookie exists
     *
     * @param  mixed   $index
     * @return boolean
     */
    public function has($index);

    /**
     * Put a value in the cookie.
     * 
     * @param  string    $index
     * @param  mixed  $value
     * @param  int  $minutes
     * @param  string  $path
     * @param  string  $domain
     * @param  bool  $secure
     * @param  bool  $httpOnly
     * @return void
     */
    public function put($index, $value, $minutes, $path, $domain, $secure, $httpOnly);

    /**
     * Put a value in the cookie for long time.
     *
     * @param  string    $index
     * @param  mixed  $value
     * @param  int  $minutes
     * @param  string  $path
     * @param  string  $domain
     * @param  bool  $secure
     * @param  bool  $httpOnly
     * @return void
     */
    public function forever($index, $value, $path, $domain, $secure, $httpOnly);

    /**
     * Gets the value in the cookie.
     * If index is null returns a default specified.
     *
     * @param  string   $index
     * @param  mixed    $default
     * @return mixed
     */
    public function get($index, $default = null);

    /**
     * Get all stored cookie values.
     *
     * @return mixed
     */
    public function all();

    /**
     * Removes cookie from the store.
     *
     * @return void
     */
    public function forget($index);
}