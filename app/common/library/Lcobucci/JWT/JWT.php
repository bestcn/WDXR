<?php
namespace Lcobucci\JWT;


use Lcobucci\JWT\Signer\Hmac\Sha256;
use Phalcon\Exception;
use Wdxr\Models\Entities\Admins;

class JWT
{

    static private $signer = null;

    static private $uid = null;

    static private $user = null;

    static public function getSigner()
    {
        if(is_null(self::$signer)) {
            self::$signer = new Sha256();
        }
        return self::$signer;
    }

    static private function getTokenKey()
    {
        return md5('youbuwei');
    }

    static public function generateToken($uid)
    {
        return (new Builder())->setIssuer('https://api.guanjia16.net') // Configures the issuer (iss claim)
        ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        ->setExpiration(time() + 86400) // Configures the expiration time of the token (nbf claim)
        ->set('uid', $uid) // Configures a new claim, called "uid"
        ->sign(self::getSigner(), self::getTokenKey()) // creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
    }

    static public function validate($token, $uid)
    {
        $token = (new Parser())->parse((string) $token);
        if($token->isExpired() || $token->verify(self::getSigner(), self::getTokenKey()) === false) {
            return false;
        }

        $data = new ValidationData();
        $data->setIssuer('https://api.guanjia16.net');
        $data->setId('4f1g23a12aa');
        $data->setCurrentTime(time());
        if($token->validate($data) === false) {
            return false;
        }

        return ($token->getClaim('uid') == $uid);
    }

    static public function getUidByToken($token)
    {
        $token = (new Parser())->parse((string) $token);
        if($token->isExpired() || $token->verify(self::getSigner(), self::getTokenKey()) === false) {
            return false;
        }

        $data = new ValidationData();
        $data->setIssuer('https://api.guanjia16.net');
        $data->setId('4f1g23a12aa');
        $data->setCurrentTime(time());
        if($token->validate($data) === false) {
            return false;
        }

        self::setUid($token->getClaim('uid'));
        return self::getUid();
    }

    static public function setUid($uid)
    {
        if(is_null(self::$uid)) {
            self::$uid = $uid;
        }
        return true;
    }

    static public function getUid()
    {
        return self::$uid;
    }

}