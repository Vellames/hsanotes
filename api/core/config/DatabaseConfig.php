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
    const HOST = "localhost";

    /**
     * Name of database to connect
     */
    const DATABASE_NAME = "mydb";

    /**
     * User to connect in database
     */
    const USER = "root";

    /**
     * Password of user to connect in database
     */
    const PASSWORD = "root";
}