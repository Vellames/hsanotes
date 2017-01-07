<?php

/**
 * Database configuration
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class DatabaseConfig{

    /**
     * Driver to use to connect to the database
     */
    const SGDB = "mysql";

    /**
     * Server of connection
     */
    const HOST = "mysql.hostinger.com.br";

    /**
     * Name of database to connect
     */
    const DATABASE_NAME = "u274649875_hsa";

    /**
     * User to connect in database
     */
    const USER = "u274649875_hsa";

    /**
     * Password of user to connect in database
     */
    const PASSWORD = "hs@notes123";
}