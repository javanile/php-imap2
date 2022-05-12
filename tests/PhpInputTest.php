<?php

namespace Javanile\MysqlImport\Tests;

use Javanile\MysqlImport\MysqlImport;
use PHPUnit\Framework\TestCase;

class MysqlImportTest extends TestCase
{
    public function testWrongArguments()
    {

        $this->assertEquals(2, $mysqlImport->getExitCode());
    }

}
