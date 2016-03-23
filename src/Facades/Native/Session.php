<?php 
namespace Xaamin\Session\Facades\Native;

use Xaamin\Session\Native\Session as SessionWrapper;

class Session extends Facade 
{	
    public static function create()
    {
        return new SessionWrapper();
    }
}