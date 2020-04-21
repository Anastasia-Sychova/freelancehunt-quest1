<?php
declare(strict_types=1);

namespace Quest1\Errors;

class SqlError extends \Exception
{
    /**
     * SqlError constructor.
     * @param null $error
     * @param null $errCode
     */
    public function __construct($error = null, $errCode = null)
    {
        parent::__construct($error, $errCode);
    }
}
