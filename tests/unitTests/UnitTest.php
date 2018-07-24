<?php

use PHPUnit\Framework\TestCase;
use App\Queries\Select;


class UnitTest extends TestCase
{
	public function testSimpleSelect()
	{
		$filtro = [];
		$select = new Select("tb_name", $filtro);		
		$res = $select->renderQuery();

		$this->assertEquals("SELECT * FROM tb_name",$res);
	}

	public function testSelectWithParams()
	{
		$filtro = [
			'attr' => [
				'id', 'name','age'
			]
		];

		$select = new Select("tb_name", $filtro);		
		$res = $select->renderQuery();

		$this->assertEquals("SELECT id,name,age FROM tb_name",$res);
	}


	public function testHasNoGroupBy()
	{
		$filtro = [];
		$select = new Select("tb_name", $filtro);	

		$hasNoGroupBy = $select->hasProperty('group_by');
		$hasNoAgregation = $select->hasProperty('aggregation');
		$this->assertFalse($hasNoGroupBy);
		$this->assertFalse($hasNoAgregation);
	}

	public function testGetGroupBy()
	{
		$filtro = [
			'group_by' => [
				'name'
			]
		];

		$select = new Select("tb_name", $filtro);
		$hasGroupBy = $select->hasProperty('group_by');
		$groupBy = $select->getGroupBy();

        $this->assertTrue($hasGroupBy);
		$this->assertEquals("GROUP BY name",$groupBy);
	}

	public function testAggregation()
	{
		$filtro = [
			'aggregation' => 'COUNT'
		];

		$select = new Select("tb_name", $filtro);	
		$hasAggregation = $select->hasProperty('aggregation');	
		$aggregation = $select->getAggregation();

		$this->assertTrue($hasAggregation);
		$this->assertEquals(",COUNT(*)",$aggregation);
	}

	public function testSimpleWhere()
	{
		$filtro = [
			'where' => [
				'name' => [
					'values' => 'PB',
					'operation' => '='
				]
			]
		];

		$select = new Select("tb_name", $filtro);
		$hasWhere = $select->hasProperty('where');		
		$where = $select->getWhere();

		$this->assertTrue($hasWhere);
		$this->assertEquals("WHERE name = 'PB'",$where);
	}

	public function testWherewithMultiplesParams()
	{
		$filtro = [
			'where' => [
				'name' => [
					'values' => 'PB',
					'operation' => '='
				],
				'age' => [
					'values' =>'18',
					'operation' => '>='
				]
			]
		];

		$select = new Select("tb_name", $filtro);
		$hasWhere = $select->hasProperty('where');		
		$where = $select->getWhere();

		$this->assertTrue($hasWhere);
		$this->assertEquals("WHERE name = 'PB' AND age >= 18",$where);
	}

	public function testSimpleOrderBy()
	{
		$filtro = [
			"order_by" => [
				[
					"attr" =>"name", 
					"order" => "DESC"
				]
			]
		];

		$select = new Select("tb_name", $filtro);
		$hasOrderBy = $select->hasProperty('order_by');
		$orderBy =  $select->getOrderBy();

		$this->assertTrue($hasOrderBy);
		$this->assertEquals("ORDER BY name DESC",$orderBy);
	}

	public function testOrderByWithMutiplusParams()
	{
		$filtro = [
			"order_by" => [
				[
					"attr" => "name", 
					"order"=> "DESC"
				],
				[
					"attr" => "age", 
					"order" => "ASC"
				],
				[
					"attr" => "uf",
					"order" => "ASC"
				]				
			]
		];

		$select = new Select("tb_name", $filtro);
		$hasOrderBy = $select->hasProperty('order_by');
		$orderBy =  $select->getOrderBy();

		$this->assertTrue($hasOrderBy);
		$this->assertEquals("ORDER BY name DESC, age ASC, uf ASC",$orderBy);
	}
}