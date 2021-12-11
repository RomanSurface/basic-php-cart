<?php

/**  coding: utf-8 **/

declare(strict_types=1);

namespace AcmeWidget;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
              session_start();
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function read(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public function write(string $key, $value): Session
    {
        $_SESSION[$key] = $value;
        return $this;
    }
}
