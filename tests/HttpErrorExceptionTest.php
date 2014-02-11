<?php

namespace TpReport;

require_once __DIR__ . '/../src/HttpErrorException.php';

/**
 * @author marek
 */
class HttpErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
    
    
    public function testInstance() {

        $this->assertInstanceOf('Exception', new HttpErrorException(404));

    }
    
    /**
     * @param int $code Error code
     *
     * @dataProvider providerTestMessages
     */
    public function testMessages($code) {
        
        $e = new HttpErrorException($code);
        $this->assertEquals($code, $e->getCode(), 'Wrong error codes');
        $this->assertNotEmpty($e->getMessage(), 'Empty error message');
        unset($e);
    }
    
    public function providerTestMessages() {
        return array(
            array(400),
            array(401),
            array(403),
            array(404),
            array(500),
            array(501),
            array(666)
            );
    }
    
}
