<?php 
namespace Xaamin\Session\Native;

use Xaamin\Session\Contracts\SessionInterface;

class Session implements SessionInterface 
{
	/**
	 * The key used in the Session.
	 *
	 * @var string
	 */
	protected $key = 'Itnovado\Session';

	/**
	 * The key used for the for flashed Session.
	 *
	 * @var string
	 */
	protected $flashnow = 'Itnovado\Session\Now';

	/**
	 * The key used for the next request .
	 *
	 * @var string
	 */
	protected $flashnext = 'Itnovado\Session\Next';

	/**
	 * Creates a new native session driver.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __construct($key = null)
	{
		if (isset($key)) $this->key = $key;

		$this->startSession();
		$this->moveFlash();
	}

	/**
	 * Called upon destruction of the native session handler.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this->writeSession();
	}

	/**
     * {@inheritdoc}
     */
	public function has($index)
	{
		if (isset($_SESSION[$this->flashnow][$index]) OR isset($_SESSION[$this->key][$index])) {
			return true;
		}

		return false;
	}

	/**
     * {@inheritdoc}
     */
	public function put($index, $value = null)
	{
		$this->setSession($index, $value);
	}

	/**
     * {@inheritdoc}
     */
	public function flash($index, $value = null)
	{
		$this->flashSession($index, $value);
	}

	/**
     * {@inheritdoc}
     */
	public function get($index, $default = null)
	{
		return $this->getSession($index, $default);
	}
	
	/**
     * {@inheritdoc}
     */
	public function pull($index, $default = null)
	{
		$session = $this->getSession($index, $default);

		$this->forget($index);

		return $session;
	}

	/**
     * {@inheritdoc}
     */
	public function all()
	{
		$values = array();
		
		if (isset($_SESSION[$this->flashnow])) {
			$values += $_SESSION[$this->flashnow];
		}

		if (isset($_SESSION[$this->key])) {
			$values += $_SESSION[$this->key];
		}

		foreach ($values as $index => $value) {
			$values[$index] = unserialize($value);
		}

		return !empty($values) ? $values : null;
	}

	/**
     * {@inheritdoc}
     */
	public function forget($index)
	{
		$this->forgetSession($index);
	}

	/**
     * {@inheritdoc}
     */
	public function flush()
	{
		$_SESSION[$this->flashnow] = $_SESSION[$this->flashnext] = 	$_SESSION[$this->key] = null;
	}

	/**
	 * Starts the session if it does not exist.
	 *
	 * @return void
	 */
	protected function startSession()
	{
		// Let's start the session
		if (session_id() == '')
		{
			session_start();
		}
	}

	/**
	 * Writes the session.
	 *
	 * @return void
	 */
	protected function writeSession()
	{
		session_write_close();
	}

	 /**
     *
     * Moves the "next" flash values to the "now" values, thereby clearing the
     * "next" values.
     *
     * @return void
     *
     */
	protected function moveFlash()
	{
		if (!isset($_SESSION[$this->flashnext])) {
            $_SESSION[$this->flashnext] = null;
        }

        $_SESSION[$this->flashnow] = $_SESSION[$this->flashnext];
        $_SESSION[$this->flashnext] = null;
	}

	/**
	 * Interacts with the $_SESSION global to set a property on it.
	 * The property is serialized initially.
	 *
	 * @param  string  $index
	 * @param  mixed  		$value
	 * @return void
	 */
	protected function setSession($index, $value)
	{
		$_SESSION[$this->key][$index] = serialize($value);
	}

	/**
	 * Interacts with the $_SESSION global to set a property on it as flashed session.
	 * The property is serialized initially.
	 *
	 * @param  string  $index
	 * @param  mixed  		$value
	 * @return void
	 */
	protected function flashSession($index, $value)
	{
		$_SESSION[$this->flashnext][$index] = serialize($value);
	}

	/**
	 * Unserializes a value from the session and returns it.
	 * If index is null returns a default specified.
	 *
	 * @param  string 	$index
	 * @param  mixed 	$defaul
	 * @return mixed.
	 */
	protected function getSession($index, $default)
	{
		if (isset($_SESSION[$this->flashnow][$index])) {
			return unserialize($_SESSION[$this->flashnow][$index]);
		} else if (isset($_SESSION[$this->key][$index])) {
			return unserialize($_SESSION[$this->key][$index]);
		} 

		return $default;
	}

	/**
	 * Forgets the Itnovado session from the global $_SESSION.
	 *
	 * @param  string  $index
	 * @return void
	 */
	protected function forgetSession($index)
	{
		if (isset($_SESSION[$this->key][$index])) {
			unset($_SESSION[$this->key][$index]);
		}
	}
}