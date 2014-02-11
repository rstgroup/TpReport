<?php

namespace TpReport;

require_once __DIR__ . '/../src/HttpErrorException.php';

/**
 * @author marek
 */
class HttpErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
    
    protected $codes = array(400, 401, 403, 404, 500, 501);
    
    public function testInstance() {

        $this->assertInstanceOf('Exception', new HttpErrorException($this->codes[0]));

    }
    
    public function testCreatingExceptions() {
        
        foreach ($this->codes as $code) {
            $e = new HttpErrorException($code);
            $this->assertEquals($code, $e->getCode(), 'Wrong error codes');
            $this->assertNotEmpty($e->getMessage(), 'Empty error message');
            unset($e);
        }
    } 
}
