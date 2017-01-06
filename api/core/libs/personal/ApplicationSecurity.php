<?php

/**
 * Class responsible to generate the hashs of application
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class ApplicationSecurity{

    /**
     * Encrypted the password with sha1.
     * @param string $password Text plain password
     * @return string Password hash
     */
    public static function generatePasswordHash(string $password) : string {
        return sha1("SDGdfhd94#$@#&#$#%@&her" . $password . "%&*hrehreth45&%$&$57");
    }

    /**
     * Generate a hash from user authentication
     * @return string Return the hash
     */
    public static function generateAuthHash() : string {
        return sha1("DGS23%@#@#00" . time() . "FDSDSG23523@#%#@236b");
    }

}