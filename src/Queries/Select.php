<?php

namespace App\Queries;

class Select
{
	protected $tablename;
	protected $filter;
	protected $query;

	public function __construct($tablename, $filter)
	{
		$this->tablename = $tablename;
		$this->filter = $filter;
		$this->query = "";
	}

	public function renderQuery()
	{
		$this->query .= "SELECT ";
		$this->query .= $this->getAtributes();
		$this->query .= " FROM ". $this->tablename;

		return preg_replace("/\s+/"," ",$this->query);
	}

	public function hasProperty($property)
	{
		return (isset($this->filter[$property])) ? true : false;
	}

	public function getAtributes()
	{
		if(!isset($this->filter['attr']))
		{
			return " * ";
		}

		return implode(",", $this->filter['attr']);
	}

	public function hasGroupBy()
	{
		return (isset($this->filter['group_by'])) ? true : false; 
	}

	public function hasAggregation()
	{
		return (isset($this->filter['aggregation'])) ? true : false;
	}

	public function getGroupBy()
	{
		$groupBy = "GROUP BY ";
		$groupBy .= implode(",", $this->filter['group_by']);
		return $groupBy;
	}

	public function getAggregation()
	{
		$aggregation = ",";
		$aggregation .= $this->filter['aggregation'];
		$aggregation .= "(*)";
		return $aggregation;
	}

	public function hasWhere()
	{
		return (isset($this->filter['where'])) ? true : false;
	}

	public function getWhere()
	{
		if(!$this->hasWhere()){
			return false;
		}

		$where = 'WHERE ';
		$wheres = [];
		foreach($this->filter['where'] as $key=>$value)
		{

			$w = '';
			$w .= $key." ";
			//$w .= $value['operation'];
			if($value['operation'] == 'IN'){
				$w .= "(".implode(",", $value['values']).") ";
			}

			if($value['operation'] == 'BOOL'){
				$w .=  " = ". $value['values'];
			}

			if(in_array($value['operation'],["=",">","<",">=","<=","<>","!="]))
			{
				$param = '';
				if(is_numeric($value['values'])){
					$param = ' '.$value['values'];
				}else{
					$param = ' '."'".$value['values']."'";
				}

				$w .= $value['operation'] . $param;
			}

			$wheres[] = $w;
		}

		$where .= implode(" AND ", $wheres);
		return $where;
	}

	public function getOrderBy()
	{ 
		$orderBy = "ORDER BY ";		

		foreach ($this->filter['order_by'] as $key => $value)
		{
				
			if($key == key($this->filter['order_by'])){
				$orderBy .= $value['attr']." ".$value['order'];
			}else{
				$orderBy .= ", ".$value['attr']." ".$value['order'];
			}		
		
		}
	

		return $orderBy;
	}

}