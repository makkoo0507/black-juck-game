<?php

namespace blackJack\test ;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/sample.php');
use blackJack\Sample; //use blackJack\Sample as Sample の省略

class SampleTest extends TestCase
{
    public function testSample()
    {
        $class = new Sample();
        $this->assertSame('sample', $class->sample());
    }
}
