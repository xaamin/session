<?php
namespace Xaamin\Session\Native;

use Xaamin\Session\Contracts\CookieInterface;

class Cookie implements CookieInterface
{
    /**
     * The cookie options.
     *
     * @var array
     */
    protected $options = [
        'name'      => 'Itnovado\Cookie',
        'domain'    => '',
        'path'      => '/',
        'secure'    => false,
        'http_only' => false,
    ];

    /**
     * Create a new cookie driver.
     *
     * @param  string|array  $options
     * @return void
     */
    public function __construct($options = [])
    {
        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
        } else {
            $this->options['name'] = $options;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has($index)
    {
        return isset($_COOKIE[$index]);
    }

    /**
     * {@inheritDoc}
     */
    public function put($index, $value, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $this->setCookie($index, $value, $this->minutesToLifetime($minutes), $path, $domain, $secure, $httpOnly);
    }

    /**
     * {@inheritDoc}
     */
    public function forever($index, $value, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $this->put($index, $value, $this->minutesToLifetime(2628000), $path, $domain, $secure, $httpOnly);
    }

    /**
     * {@inheritDoc}
     */
    public function get($index, $default = null)
    {
        return $this->getCookie($index, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        $cookies = [];

        foreach ($_COOKIE as $index => $value) {
            if ($value = json_decode($value)) {
                $cookies[$index] = $value;
            }
        }

        return $cookies;
    }

    /**
     * {@inheritDoc}
     */
    public function forget($index)
    {
        $this->put($index, null, -2628000);
    }

    /**
     * Takes a minutes parameter (relative to now)
     * and converts it to a lifetime (unix timestamp).
     *
     * @param  int  $minutes
     * @return int
     */
    protected function minutesToLifetime($minutes)
    {
        return time() + ($minutes * 60);
    }

    /**
     * Returns a PHP cookie.
     *
     * @return mixed
     */
    protected function getCookie($index, $default)
    {
        if (isset($_COOKIE[$index])) {
            $value = $_COOKIE[$index];

            if ($value) {
                return json_decode($value);
            }
        }

        return $default;
    }

    /**
     * Sets a PHP cookie.
     *
     * @param  string   $index
     * @param  mixed    $value
     * @param  int      $minutes
     * @param  string   $path
     * @param  string   $domain
     * @param  bool     $secure
     * @param  bool     $httpOnly
     * @return void
     */
    protected function setCookie($index, $value, $minutes, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        setcookie(
            $index,
            json_encode($value),
            $minutes,
            $path ? : $this->options['path'],
            $domain ? : $this->options['domain'],
            $secure ? : $this->options['secure'],
            $httpOnly ? : $this->options['http_only']
        );
    }
}