<?php

namespace TpReport;

require_once __DIR__ . '/../src/Request.php';

/**
 * Tests for TpReport\Request class.
 *
 * @author Marek Ziółkowski
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    protected $request;
    private $api_base_url;

    protected function setUp()
    {
        $this->api_base_url = 'http://localhost';
        $this->request = new Request($this->api_base_url, null, null);
    }

    public function testFullUrlBuilding()
    {
        $url = $this->request->collection('UserStories')
                        ->where(array(
                            'and',
                            array('in', 'Project.Id', array(1, 2, 3)),
                            array('in', 'EntityState.Name', array('New', 'In progress')),
                        ))->inc(array('Id', 'Name'))->take(666)->getUrl();

        $this->assertEquals(
                $this->api_base_url . "/UserStories?where=(Project.Id in (1,2,3)) and (EntityState.Name in ('New','In progress'))&include=[Id,Name]&take=666", $url);
    }

    /**
     * Testing where conditions.
     * 
     * @param string|array $where Conditions
     * @param string $collection
     * @param string $expected_url
     * @dataProvider providerTestWhere
     */
    public function testWhere($where, $collection, $expected_url)
    {

        $url = $this->request->collection($collection)->where($where)->getUrl();
        $this->assertEquals($this->api_base_url . $expected_url, $url);
    }

    public function providerTestWhere()
    {
        return array(
            array(array(), 'Bugs', '/Bugs'),
            array('Project.Id in (1,2,3)', 'Bugs', '/Bugs?where=Project.Id in (1,2,3)'),
            array(array('in', 'Project.Id', array(1, 2, 3)), 'Bugs', '/Bugs?where=Project.Id in (1,2,3)'),
            array(
                array(
                    'and',
                    array('in', 'Project.Id', array(1, 2, 3)),
                    array('in', 'EntityState.Name', array('New', 'In progress'))
                ),
                'Bugs',
                "/Bugs?where=(Project.Id in (1,2,3)) and (EntityState.Name in ('New','In progress'))"
            )
        );
    }

    public function testWhereUnknownOperator()
    {
        $wrong_operator = 'aaand';
        $this->setExpectedException(
                '\Exception', 'Unknown operator ' . $wrong_operator
        );
        $this->request->collection('Bugs')->where(array($wrong_operator, 'x1', 'x2'))->getUrl();
    }

    public function testUndefinedCollection()
    {
        $this->setExpectedException(
                '\Exception', "Can't build URL: undefined collection"
        );
        $this->request->getUrl();
    }

    public function testTake()
    {
        $url = $this->request->collection('Bugs')->take(666)->getUrl();
        $this->assertEquals($this->api_base_url . '/Bugs?take=666', $url);
    }
    
    /**
     * 
     * @param array $include
     * @param string $expected_url
     * @dataProvider providerTestInclude
     */
    public function testInclude($include, $expected_url)
    {
        $url = $this->request->collection('Bugs')->inc($include)->getUrl();
        $this->assertEquals($this->api_base_url . $expected_url, $url);
    }
    
    public function providerTestInclude()
    {
        return array(
            array(array('Id', 'Name'), '/Bugs?include=[Id,Name]'),
            array(array('Id'), '/Bugs?include=[Id]'),
            array(array('Project.Id', 'Project.Name'), '/Bugs?include=[Project.Id,Project.Name]')
        );
    }

}
