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

		$hasNoGroupBy = $select->hasGroupBy();
		$hasNoAgregation = $select->hasAggregation();
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
		$hasGroupBy = $select->hasGroupBy();
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
		$hasAggregation = $select->hasAggregation();	
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
		$hasWhere = $select->hasWhere();		
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
		$hasWhere = $select->hasWhere();		
		$where = $select->getWhere();

		$this->assertTrue($hasWhere);
		$this->assertEquals("WHERE name = 'PB' AND age >= 18",$where);
	}
}