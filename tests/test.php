<?php
use PHPUnit\Framework\TestCase;

final class PincodeDirectoryTest extends TestCase
{
    protected $client;
    
    public function setUp()
    {
		$this->client = new Liridian\PincodeDirectory();
    }

    public function testAvailableFilters()
    {
        
        $available_filters = $this->client->getAvailableFilters();
        $this->assertNotNull($available_filters	);
        $this->assertInternalType('array', $available_filters);
    }

    public function testAvailableSeletFields()
    {
    	$available_fields = $this->client->getAvailableSelectFields();
        $this->assertNotNull($available_fields);
        $this->assertInternalType('array', $available_fields);
    }

    public function testSetApiKey()
    {
    	$api_key = 'api_key';
    	$this->client->withApiKey($api_key);
    	$this->assertNotNull($this->client->api_key);
        $this->assertEquals($api_key, $this->client->api_key);
    }

    public function testSetOffset()
    {
        $offset = 100;
        $this->client->withOffset($offset);
        $params = $this->client->getparams();
        $this->assertNotNull($params);
        $this->assertEquals($offset, $params['query']['offset']);
    }

    public function testSetLimit()
    {
        $limit = 10;
        $this->client->withLimit($limit);
        $params = $this->client->getparams();
        $this->assertNotNull($params);
        $this->assertEquals($limit, $params['query']['limit']);
    }

    public function testSetSort()
    {
        $this->client->withSort([['pincode' => 'asc'], ['regionname' => 'desc']]);
        $params = $this->client->getparams();
        $this->assertNotNull($params);
        $this->assertEquals('asc', $params['query']['sort[pincode]']);
        $this->assertEquals('desc', $params['query']['sort[regionname]']); 
    }

    /**
    * @expectedException InvalidArgumentException
    * @expectedExceptionMessage Invalid sort index provided
    */
    public function testInvalidSetSort()
    {
        $this->client->withSort([['Taluk' => 'asc'], ['regionname' => 'desc']]);
    }

    public function testValidFilter()
    {
        $this->client->withFilter([['pincode' => '400089'], ['regionname' => 'Mumbai']]);
        $params = $this->client->getparams();
        $this->assertNotNull($params);
        $this->assertEquals('400089', $params['query']['filters[pincode]']);
        $this->assertEquals('Mumbai', $params['query']['filters[regionname]']);   
    }

    /**
    * @expectedException InvalidArgumentException
    * @expectedExceptionMessage Invalid filter provided
    */
    public function testInvalidFilter()
    {
        $this->client->withFilter([['Taluk' => 'Mumbai']]);
    }

    public function testWithAllFields()
    {
        $this->client->withFields(['*']);
        $params = $this->client->getparams();
        $this->assertNotNull($params['query']['fields']);
    }

    public function testWithSomeValidFields()
    {
        $this->client->withFields(['pincode']);
        $params = $this->client->getparams();
        $this->assertNotNull($params['query']['fields']);
    }

    /**
    * @expectedException InvalidArgumentException
    * @expectedExceptionMessage Invalid select fields provided
    */    
    public function testWithSomeInvalidFields()
    {
        $this->client->withFields(['Taluk']);
    }

    public function testWithNoFieldsPassed()
    {
        $this->client->withFields();
        $params = $this->client->getparams();
        $this->assertNotNull($params['query']['fields']);
    }

    public function testPositiveGet()
    {
        $result = $this->client->withApiKey(getenv('api_key'))->get();
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('records', $result);
    }

    public function testNegativeGet()
    {
        $result = $this->client->get();
        $this->assertArrayHasKey('status_code', $result);
        $this->assertArrayHasKey('message', $result);
    }
}
