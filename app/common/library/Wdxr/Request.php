<?php
namespace Wdxr;


class Request
{

    /**
     * @return \Phalcon\Http\Request
     */
    static public function getRequest()
    {
        return \Phalcon\Di::getDefault()->get('request');
    }

    /**
     * @return string
     */
    static public function getDomain()
    {
        $proto = (self::getRequest()->getServer('HTTP_X_FORWARDED_PROTO') ? : 'http') . "://";
        $host = \Phalcon\Di::getDefault()->get('config')->get('domain');
        $host = $proto . $host;

        return $host;
    }

}