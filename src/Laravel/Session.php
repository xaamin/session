<?php 
namespace Xaamin\Session\Laravel;

use Xaamin\Session\Contracts\SessionInterface;
use Illuminate\Session\Store as SessionStore;

class Session implements SessionInterface 
{
	/**
	 * The key used in the Session.
	 *
	 * @var string
	 */
	protected $key = 'Itnovado\Session';

	/**
	 * Session store object.
	 *
	 * @var \Illuminate\Session\Store
	 */	
	protected $session;

	/**
	 * Creates a new Illuminate based Session driver.
	 *
	 * @param  \Illuminate\Session\Store  $session
	 * @param  string  $key
	 * @return void
	 */
	public function __construct(SessionStore $session, $key = null)
	{
		$this->session = $session;

		if ($key) $this->key = $key;
	}

	/**
     * {@inheritdoc}
     */
	public function has($index)
	{
		return $this->session->has($index);
	}

	/**
     * {@inheritdoc}
     */
	public function put($index, $value = null)
	{
		$this->session->put($index, $value);
	}

	/**
     * {@inheritdoc}
     */
	public function flash($index, $value = null)
	{
		$this->session->flash($index, $value);
	}

	/**
     * {@inheritdoc}
     */
	public function get($index, $default = null)
	{
		return $this->session->get($index, $default);
	}
	
	/**
     * {@inheritdoc}
     */
	public function pull($index, $default = null)
	{
		return $this->session->pull($index, $default);
	}

	/**
     * {@inheritdoc}
     */
	public function forget($index)
	{
		$this->session->forget($index);
	}

	/**
     * {@inheritdoc}
     */
	public function all()
	{
		return $this->session->all();
	}
}