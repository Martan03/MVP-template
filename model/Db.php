<?php

class Db
{
    /**
     * @var PDO Database connection
     */
    private static PDO $connection;
    /**
     * @var array Default settings
     */
    private static array $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Connects to database using given params
     * @param string $host name
     * @param string $database name
     * @param string $user name
     * @param string $password
     * @return bool True on success, else false
     */
    public static function connect(
        string $host,
        string $database,
        string $user,
        string $password
    ) : bool
    {
        if (isset(self::$connection))
            return true;
        
        try
        {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$options
            );
        }
        catch (PDOException $e)
        {
            return false;
        }

        return true;
    }

    /**
     * Executes query and prevents SQL injection
     * @param string $query to the database
     * @param array $params containing params in query - optional
     * @return mixed PDOStatement, false when failure occurs
     */
    private static function execute(
        string $query,
        array $params = array()
    ) : PDOStatement|bool
    {
        $ret = self::$connection->prepare($query);
        $ret->execute($params);
        return $ret;
    }

    /**
     * Executes query and safely inserts params to prevent SQL injection
     * @param string $query to the database
     * @param array $params containing params in query - optional
     * @return int Number of effected rows
     */
    public static function query(
        string $query,
        array $params = array()
    ) : int
    {
        return self::execute($query, $params)->rowCount();
    }

    /**
     * Executes query and safely inserts params to prevent SQL injection
     * @param string $query to the database
     * @param array $params containing params in query - optional
     * @return array all fetched rows from the database 
     */
    public static function queryAll(
        string $query,
        array $params = array()
    ) : array
    {
        return self::execute($query, $params)->fetchAll();
    }

    /**
     * Executes query and safely inserts params to prevent SQL injection
     * @param string $query to the database
     * @param array $params containing params in query - optional
     * @return mixed first fetched item, else false
     */
    public static function queryOne(
        string $query,
        array $params = array()
    ) : array|bool
    {
        return self::execute($query, $params)->fetch();
    }

    /**
     * Executes query and safely inserts params to prevent SQL injection
     * @param string $query to the database
     * @param array $params containing params in query - optional
     * @return string value from the first column from first fetched row
     */
    public static function querySingle(
        string $query,
        array $params = array()
    ) : string
    {
        return self::queryOne($query, $params)[0];
    }

    /**
     * Inserts given data to the databse
     * @param string $table name to be inserted in
     * @param array $data asociative array, key == database column name
     * @return bool True on success, else false
     */
    public static function insert(
        string $table,
        array $data = array()
    ) : bool
    {
        return self::query("
            INSERT INTO `$table`
            (`" . implode('`, `', array_keys($data)) . "`)
            VALUES (" . str_repeat('?,', count($data) - 1) . "?)
        ", array_values($data));
    }

    /**
     * Updates row based on given condition
     * @param string $table name to be updated in
     * @param array $data asociative array, key == database column name
     * @param string $condition to get row to update
     * @param array $params condition parameters
     * @return bool True on success, else false
     */
    public static function update(
        string $table,
        array $data = array(),
        string $condition,
        array $params = array()
    ) : bool
    {
        return self::query("
            UPDATE `$table`
            SET `" . implode('` = ?, `', array_keys($data)) . "` = ?
            " . $condition . "
        ", array_merge(array_values($data), array_values($params)));
    }

    /**
     * Returns last inserted ID
     * @return int Last inserted ID
     */
    public static function getLastId() : int
    {
        return self::$connection->lastInsertId();
    }
}