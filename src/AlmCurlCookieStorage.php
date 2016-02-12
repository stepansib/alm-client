<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 12:32
 */

namespace StepanSib\AlmClient;

class AlmCurlCookieStorage
{

    const SESSION_COOKIE_FILE_KEY = "alm_cookie_file";

    public function createCurlCookieFile()
    {
        if (!$this->isCurlCookieFileExist()) {
            $_SESSION[self::SESSION_COOKIE_FILE_KEY] = tempnam("/tmp", "ALMCOOKIE");
        }

        return $this;
    }

    /**
     *
     */
    public function deleteCurlCookieFile()
    {
        if ($this->isCurlCookieFileExist()) {
            unlink($_SESSION[self::SESSION_COOKIE_FILE_KEY]);
            unset($_SESSION[self::SESSION_COOKIE_FILE_KEY]);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurlCookieFile()
    {
        if ($this->isCurlCookieFileExist()) {
            return $_SESSION[self::SESSION_COOKIE_FILE_KEY];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isCurlCookieFileExist()
    {
        if (isset($_SESSION[self::SESSION_COOKIE_FILE_KEY])) {
            if (file_exists($_SESSION[self::SESSION_COOKIE_FILE_KEY])) {
                return true;
            }
        }
        return false;
    }

}
