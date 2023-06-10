<?php

namespace Lib;
use PDO;
use Lib\env;

class Mysql{

    private static null|PDO $cnx = null;

    /**
     * @param $cdn
     * @return PDO
     */
    private static function connect():PDO
    {
        if (is_null(self::$cnx))
        {
            $dcn = sprintf("mysql:host=%s:%s;dbname=%s;",
                            env('DB_HOST', "127.0.0.1"),
                            env('DB_PORT', "3306"),
                            env('DB_DATABASE'));
            self::$cnx = new PDO($dcn,env('DB_USERNAME'),env('DB_PASSWORD'));
        }
        return self::$cnx;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    private static function exec(string $sql, array $params = [])
    {
        try{
            $cnx = self::connect();
            $statement = $cnx->prepare($sql);
            $statement->execute($params);
            return $statement;
        }catch(\PDOException $er){
            echo $sql;
            throw new \Exception($sql."---".$er->getMessage());
        }
        return false;
    }

    /**
     * @param string $key
     * @param int $seconds
     * @param mixed $data
     * @return mixed
     */
    private static function isValid(string $key, int $seconds):mixed
    {
        $delete_sql = "delete from cache where ckey=? and TIMESTAMPDIFF(second, now(), TIMESTAMPADD(SECOND,?,created_at)) <= 0";
        $fetch_sql  = "select cdata from cache where ckey=?";
        $del_result = self::exec($delete_sql, [$key, $seconds]);
        $fetch_result = self::exec($fetch_sql, [$key]);
        if($fetch_result instanceof \PDOStatement){
            return $fetch_result->fetch(PDO::FETCH_COLUMN);
        }
        return false;
    }

    /**
     * @param string $key
     * @param int $seconds
     * @param mixed $data
     * @return mixed
     */
    public static function remember(string $key, int $seconds, callable $func):mixed
    {
        $stillValid = self::isValid($key, $seconds);
        if(!$stillValid)
        {
            echo "valide for $seconds secondes";
            $data = $func();
            self::store($key, $seconds, $data);
            return $data;
        }
        return unserialize($stillValid);
    }

    /**
     * @param string $key
     * @param int $seconds
     * @param mixed $data
     * @return bool
     */
    public static function store(string $key, int $seconds, mixed $data):bool
    {
        $sql = "insert into cache(ckey, ctype, cdata, created_at) values (?, ?, ?, now()) ";
        self::forget($key);
        $data = serialize($data);
        self::exec($sql, [$key, gettype($data), $data]);
        return true;
    }

    /**
     * @param $key
     */
    public static function forget($key)
    {
        $sql = "delete from cache where ckey=?";
        self::exec($sql, ["$key"]);
    }
}