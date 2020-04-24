<?php
namespace Quest1\Database;

use \Quest1\Errors\ConnectionError;
use \Quest1\Errors\QueryError;

/**
 * @method string real_escape_string(string $escapeString)
 */
class Mysql
{
    /**
     * @var \mysqli
     */
    protected $mysqli;

    /**
     * @var array
     */
    private $params;
    private static $errors = [
        1040,// Too many connections
        2006,// Server has gone away
    ];

    /**
     * Mysql constructor.
     * @param array $connectionData
     */
    public function __construct(array $connectionData = [])
    {
        $this->params = $connectionData + [
            'host'   => '127.0.0.1',
            'user'   => 'root',
            'pass'   => '123456',
            'db'     => 'quest1',
            //'port'   => ini_get('mysqli.default_port'),
            //'socket' => ini_get('mysqli.default_socket'),
        ];
    }

    /**
     * @param $key
     *
     * @return mixed
     *
     * @throws ConnectionError
     */
    public function __get($key)
    {
        if (!$this->mysqli)
            $this->connect();

        return $this->mysqli->$key;
    }

    /**
     * @throws ConnectionError
     */
    protected function connect()
    {
        $this->mysqli = @new \mysqli(
            $this->params['host'],
            $this->params['user'],
            $this->params['pass'],
            $this->params['db'],
            $this->params['port'],
            $this->params['socket']
        );

        if ($this->mysqli->connect_error)
            throw new ConnectionError($this->mysqli->connect_error, $this->mysqli->connect_errno);

        $this->mysqli->set_charset('utf8');
    }

    /**
     * @param $func
     * @param array $params
     *
     * @return mixed
     *
     * @throws ConnectionError
     */
    public function __call($func, array $params)
    {
        if (!$this->mysqli)
            $this->connect();

        return call_user_func_array(array($this->mysqli, $func), $params);
    }

    /**
     * @param string $query
     *
     * @return bool|\mysqli_result
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function query($query)
    {
        if (!$this->mysqli)
            $this->connect();

        $result = $this->mysqli->query($query);
        if ($result === false && in_array((int) $this->mysqli->errno, self::$errors)) {
            throw new ConnectionError($this->mysqli->error, $this->mysqli->errno);
        }
        if ($result === false && $this->mysqli->errno && !in_array((int) $this->mysqli->errno, self::$errors)) {
            throw new QueryError($query . "\n" . $this->mysqli->error, $this->mysqli->errno);
        }

        return $result;
    }
}
