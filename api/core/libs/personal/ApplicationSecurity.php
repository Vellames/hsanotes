<?php

class ApplicationSecurity{

    /**
     * Encrypted the password with sha1.
     * @param string $password Text plain password
     * @return string Password hash
     */
    public static function generatePasswordHash(string $password) : string {
        return sha1("SDGdfhd94#$@#&#$#%@&her" . $password . "%&*hrehreth45&%$&$57");
    }

}