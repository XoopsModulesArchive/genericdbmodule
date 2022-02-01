<?php

/**
 * @version   $Id: token.php 10050 2012-08-11 07:19:07Z beckmi $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <http://xoopscube.sourceforge.net>
 * @license   http://xoopscube.sourceforge.net/license/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 */
define('XOOPS_TOKEN_TIMEOUT', 0);
define('XOOPS_TOKEN_PREFIX', 'XOOPS_TOKEN_');

if (!defined('XOOPS_SALT')) {
    define('XOOPS_SALT', mb_substr(md5(XOOPS_DB_PREFIX . XOOPS_DB_USER . XOOPS_ROOT_PATH), 5, 8));
}

define('XOOPS_TOKEN_SESSION_STRING', 'X2_TOKEN');
define('XOOPS_TOKEN_MULTI_SESSION_STRING', 'X2_MULTI_TOKEN');

define('XOOPS_TOKEN_DEFAULT', 'XOOPS_TOKEN_DEFAULT');

/**
 * This class express token. this has name, token's string for inquiry,
 * lifetime, serial number. this does not have direct validation method,
 * therefore this does not depend on $_Session and $_Request.
 *
 * You can refer to a handler class for this token. this token class
 * means ticket, and handler class means ticket agent. there is a strict
 * ticket agent type(XoopsSingleTokenHandler), and flexible ticket agent
 * for the tab browser(XoopsMultiTokenHandler).
 */
class XoopsToken
{
    /**
     * token's name. this is used for identification.
     */
    public $_name_;
    /**
     * token's string for inquiry. this should be a random code for security.
     */
    public $_token_;
    /**
     * the unixtime when this token is effective.
     */
    public $_lifetime_;
    /**
     * unlimited flag. if this is true, this token is not limited in lifetime.
     */
    public $_unlimited_;
    /**
     * serial number. this used for identification of tokens of same name tokens.
     */
    public $_number_ = 0;

    /**
     * @param this $name    token's name string.
     * @param int  $timeout effective time(if $timeout equal 0, this token will become unlimited)
     */
    public function __construct($name, $timeout = XOOPS_TOKEN_TIMEOUT)
    {
        $this->_name_ = $name;

        if ($timeout) {
            $this->_lifetime_  = time() + $timeout;
            $this->_unlimited_ = false;
        } else {
            $this->_lifetime_  = 0;
            $this->_unlimited_ = true;
        }

        $this->_token_ = $this->generateToken();
    }

    /**
     * Returns random string for token's string.
     *
     * @return string
     */
    public function generateToken(): string
    {
        // mt_srand(microtime() * 100000);

        return md5(XOOPS_SALT . $this->_name_ . uniqid(mt_rand(), true));
    }

    /**
     * Returns this token's name.
     *
     * @return string
     */
    public function getTokenName(): string
    {
        return XOOPS_TOKEN_PREFIX . $this->_name_ . '_' . $this->_number_;
    }

    /**
     * Returns this token's string.
     *
     * @return  string
     */
    public function getTokenValue(): string
    {
        return $this->_token_;
    }

    /**
     * Set this token's serial number.
     *
     * @param serial $serial_number number
     */
    public function setSerialNumber($serial_number): void
    {
        $this->_number_ = $serial_number;
    }

    /**
     * Returns this token's serial number.
     *
     * @return  int
     */
    public function getSerialNumber(): int
    {
        return $this->_number_;
    }

    /**
     * Returns hidden tag string that includes this token. you can use it
     * for <form> tag's member.
     *
     * @return  string
     */
    public function getHtml(): string
    {
        return @sprintf('<input type="hidden" name="%s" value="%s">', $this->getTokenName(), $this->getTokenValue());
    }

    /**
     * Returns url string that includes this token. you can use it for
     * hyper link.
     *
     * @return  string
     */
    public function getUrl(): string
    {
        return $this->getTokenName() . '=' . $this->getTokenValue();
    }

    /**
     * If $token equals this token's string, true is returened.
     *
     * @param null|mixed $token
     * @return  bool
     */
    public function validate($token = null): bool
    {
        return ($this->_token_ == $token && ($this->_unlimited_ || time() <= $this->_lifetime_));
    }
}

/**
 * This class express ticket agent and ticket collector. this publishes
 * token, keeps a token to server to check it later(next request).
 *
 * You can create various agents by extending the derivative class. see
 * default(sample) classes.
 */
class XoopsTokenHandler
{
    public $_prefix = '';

    /**
     * Create XoopsToken instance, regist(keep to server), and returns it.
     *
     * @param this $name    token's name string.
     * @param int  $timeout effective time(if $timeout equal 0, this token will become unlimited)
     * @return \XoopsToken
     */
    public function create($name, $timeout = XOOPS_TOKEN_TIMEOUT)
    {
        $token = new XoopsToken($name, $timeout);
        $this->register($token);

        return $token;
    }

    /**
     * Fetches from server side, and returns it.
     *
     * @param   $name token's name string.
     * @return XoopsToken
     */
    public function fetch($name)
    {
        $ret = null;
        if (isset($_SESSION[XOOPS_TOKEN_SESSION_STRING][$this->_prefix . $name])) {
            $ret = unserialize($_SESSION[XOOPS_TOKEN_SESSION_STRING][$this->_prefix . $name]);
        }

        return $ret;
    }

    /**
     * Register token to session.
     * @param mixed $token
     */
    public function register($token)
    {
        $_SESSION[XOOPS_TOKEN_SESSION_STRING][$this->_prefix . $token->_name_] = serialize($token);
    }

    /**
     * Unregister token to session.
     * @param mixed $token
     */
    public function unregister($token)
    {
        unset($_SESSION[XOOPS_TOKEN_SESSION_STRING][$this->_prefix . $token->_name_]);
    }

    /**
     * If a token of the name that equal $name is registered on session,
     * this method will return true.
     *
     * @param   $name token's name string.
     * @return  bool
     */
    public function isRegistered($name)
    {
        return isset($_SESSION[XOOPS_TOKEN_SESSION_STRING][$this->_prefix . $name]);
    }

    /**
     * This method takes out token's string from Request, and validate
     * token with it. if it passed validation, this method will return true.
     *
     * @param XoopsToken $token
     * @param bool       $clearIfValid if token passed validation, $token will be unregistered.
     * @return bool
     */
    public function validate($token, $clearIfValid): bool
    {
        $req_token = isset($_REQUEST[$token->getTokenName()]) ? trim($_REQUEST[$token->getTokenName()]) : null;

        if ($req_token) {
            if ($token->validate($req_token)) {
                if ($clearIfValid) {
                    $this->unregister($token);
                }

                return true;
            }
        }

        return false;
    }
}

/**
 *
 */
class XoopsSingleTokenHandler extends XoopsTokenHandler
{
    /**
     * @param $name
     * @param $clearIfValid
     * @return bool
     */
    public function autoValidate($name, $clearIfValid = true): bool
    {
        if ($token = $this->fetch($name)) {
            return $this->validate($token, $clearIfValid);
        }

        return false;
    }

    /**
     * static method.
     * This method was created for quick protection of default modules.
     * this method will be deleted in the near future.
     * @param mixed $name
     * @param mixed $timeout
     * @return \XoopsToken
     * @deprecated
     */
    public function quickCreate($name, $timeout = XOOPS_TOKEN_TIMEOUT): \XoopsToken
    {
        $handler = new self();
        $ret     = $handler->create($name, $timeout);

        return $ret;
    }

    /**
     * static method.
     * This method was created for quick protection of default modules.
     * this method will be deleted in the near future.
     * @param mixed $name
     * @param mixed $clearIfValid
     * @return bool
     * @deprecated
     */
    public function quickValidate($name, $clearIfValid = true): bool
    {
        $handler = new self();

        return $handler->autoValidate($name, $clearIfValid);
    }
}

/**
 * This class publish a token of the different same name of a serial number
 * for the tab browser.
 */
class XoopsMultiTokenHandler extends XoopsTokenHandler
{
    /**
     * @param $name
     * @param $timeout
     * @return \XoopsToken
     */
    public function create($name, $timeout = XOOPS_TOKEN_TIMEOUT)
    {
        $token = new XoopsToken($name, $timeout);
        $token->setSerialNumber($this->getUniqueSerial($name));
        $this->register($token);

        return $token;
    }

    /**
     * @param string          $name
     * @param string|int|null $serial_number
     * @return mixed|\XoopsToken|null
     */
    public function fetch($name, $serial_number=null)
    {
        $ret = null;
        if (isset($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$this->_prefix . $name][$serial_number])) {
            $ret = unserialize($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$this->_prefix . $name][$serial_number]);
        }

        return $ret;
    }

    /**
     * @param $token
     * @return void
     */
    public function register($token)
    {
        $_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$this->_prefix . $token->_name_][$token->getSerialNumber()] = serialize($token);
    }

    /**
     * @param $token
     * @return void
     */
    public function unregister($token)
    {
        unset($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$this->_prefix . $token->_name_][$token->getSerialNumber()]);
    }

    /**
     * @param $name
     * @param $serial_number
     * @return bool
     */
    public function isRegistered($name, $serial_number = null)
    {
        return isset($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$this->_prefix . $name][$serial_number]);
    }

    /**
     * @param $name
     * @param $clearIfValid
     * @return bool
     */
    public function autoValidate($name, $clearIfValid = true): bool
    {
        $serial_number = $this->getRequestNumber($name);
        if (null !== $serial_number) {
            if ($token = $this->fetch($name, $serial_number)) {
                return $this->validate($token, $clearIfValid);
            }
        }

        return false;
    }

    /**
     * static method.
     * This method was created for quick protection of default modules.
     * this method will be deleted in the near future.
     * @param mixed $name
     * @param mixed $timeout
     * @return \XoopsToken
     * @deprecated
     */
    public static function quickCreate($name, $timeout = XOOPS_TOKEN_TIMEOUT): \XoopsToken
    {
        $handler = new self();
        $ret     = $handler->create($name, $timeout);

        return $ret;
    }

    /**
     * static method.
     * This method was created for quick protection of default modules.
     * this method will be deleted in the near future.
     * @param mixed $name
     * @param mixed $clearIfValid
     * @return bool
     * @deprecated
     */
    public static function quickValidate($name, $clearIfValid = true): bool
    {
        $handler = new self();

        return $handler->autoValidate($name, $clearIfValid);
    }

    /**
     * @param string $name
     * @return  int
     */
    public function getRequestNumber($name): ?int
    {
        $str = XOOPS_TOKEN_PREFIX . $name . '_';
        foreach ($_REQUEST as $key => $val) {
            if (preg_match('/' . $str . '(\d+)/', $key, $match)) {
                return (int)$match[1];
            }
        }

        return null;
    }

    /**
     * @param $name
     * @return int
     */
    public function getUniqueSerial($name): int
    {
        if (isset($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$name])) {
            if (is_array($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$name])) {
                for ($i = 0; isset($_SESSION[XOOPS_TOKEN_MULTI_SESSION_STRING][$name][$i]); ++$i) {
                }

                return $i;
            }
        }

        return 0;
    }
}
