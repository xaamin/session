<?php
namespace Xaamin\Session\Illuminate;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Xaamin\Session\Contracts\CookieInterface;

class Cookie implements CookieInterface
{
    /**
     * The cookie options.
     *
     * @var array
     */
    protected $options = [
        'name'      => 'ItnovadoCookie',
        'domain'    => '',
        'path'      => '/',
        'secure'    => false,
        'http_only' => false,
    ];

    /**
     * Create a new cookie driver.
     *
     * @return void
     */
    public function __construct(Request $request, CookieJar $cookie)
    {
        $this->request = $request;
        $this->cookie = $cookie;
    }

    /**
     * {@inheritDoc}
     */
    public function has($index)
    {
        return !is_null($this->request->cookie($index, null));
    }

    /**
     * {@inheritDoc}
     */
    public function put($index, $value, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $cookie = $this->cookie->make($index, $value, $minutes, $path, $domain, $secure, $httpOnly);

        $this->cookie->queue($cookie);
    }

    /**
     * {@inheritDoc}
     */
    public function forever($index, $value, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $cookie = $this->cookie->forever($index, $value, $path, $domain, $secure, $httpOnly);

        $this->cookie->queue($cookie);
    }

    /**
     * {@inheritDoc}
     */
    public function get($index, $default = null)
    {
        $queued = $this->cookie->getQueuedCookies();

        if (isset($queued[$index])) {
            return $queued[$index];
        }

        return $this->request->cookie($index, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->cookie->getQueuedCookies() + $this->request->cookie();
    }

    /**
     * {@inheritDoc}
     */
    public function forget($index)
    {
        $cookie = $this->cookie->forget($index);

        $this->cookie->queue($cookie);
    }
}