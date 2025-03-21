<?php

namespace Vosiz\VaTools\Structure;

class Credentials {

    const PROT_PASS_PLACEH = "******"; // placeholder for protected password

    private $User;          public function GetUser()       { return $this->User;               }
    private $Password;      public function GetPassword()   { return $this->GetProtectedPass(); }
    private $Protection;    public function GetProtection() { return $this->Protection;         }

    /**
     * Constructor - user, password and protection
     */
    public function __construct(string $user, string $pass = '', $protection = false) {

        $this->User = $user;
        $this->Password = $pass;
        $this->Protection = $protection;
    }

    /**
     * Authorize
     * @param string $user user string
     * @param string $pass password string
     * @return bool when autorize
     */
    public function Auth(string $user, string $pass) {

        if($user === $this->User && $pass === $this->Password)
            return true;

        return false;
    }

    /**
     * Returns password (or placeholder)
     */
    public function GetProtectedPass() {

        if($this->Protection)
            return self::PROT_PASS_PLACEH;

        return $this->Password;
    }
}
