<?php
namespace common\utils;
/**
 * Created by PhpStorm.
 * User: pngzy
 * Date: 12/12/2016
 * Time: 11:18 AM
 */
class ArrayUtil{

    /**
     * 获得指定行的指定列值
     * @param $target_array
     * @param $index
     * @param $column_name
     * @param string $default_val
     * @return string
     */
    public static function getArrayColumnValue($target_array, $index, $column_name,$default_val=''){
        if(!isset($target_array[$index]) || empty($target_array[$index])){
            return $default_val;
        }

        if(empty($target_array[$index][$column_name])){
            return $default_val;
        }

        return $target_array[$index][$column_name];
    }

    //多维数组指定其中一列索引 todo 没有考虑建重复问题
    public static function convertArrayIndex($targetArray, $indexKey){
        if(!$targetArray) return array();

        $curr = current($targetArray);

        if($curr instanceof CActiveRecord){
        	$curr = $curr->getAttributes();
        }


        if(is_array($curr) && key_exists($indexKey, $curr)){
            $temp = array();
            foreach ($targetArray as $item){
                $temp[$item[$indexKey]] = $item;
            }
            return $temp;
        }
        return $targetArray;
    }
    //多维数组指定其中一列索引 如果键重复，数据将会以数组合并
    public static function convertArrayIndexWithMerge($targetArray, $indexKey){
        if(is_array(current($targetArray)) && key_exists($indexKey, current($targetArray))){
            $temp = array();
            foreach ($targetArray as $item){
                if(!key_exists($item[$indexKey], $temp)){
                    $temp[$item[$indexKey]] = array();
                }
                array_push($temp[$item[$indexKey]], $item);
            }
            return $temp;
        }
        return $targetArray;
    }
    // 多维数组指定其中一列索引 如果键重复，数据将会以数组合并 返回对应的二维数组索引
    public static function convertArrayIndexByColumns($targetArray, $indexKey, $return_columns = []) {
        if(is_array(current($targetArray)) && key_exists($indexKey, current($targetArray))){
            $temp = array();
            foreach ($targetArray as $item){
                if(!key_exists($item[$indexKey], $temp)){
                    $temp[$item[$indexKey]] = array();
                }

                foreach ($return_columns as $column) {
                    if (isset($item[$column])) {
                        $temp[$item[$indexKey]][$column][] = $item[$column];
                    }
                }
            }
            return $temp;
        }
        return $targetArray;
    }

    //提取数组中某一列的数据，以数组返回
    public static function getColumnFromArray($targetArray, $columnName, $keepKey = false){
     	if(!$targetArray) return array();

        $curr = current($targetArray);

        if($curr instanceof CActiveRecord){
        	$curr = $curr->getAttributes();
        }
        if(!is_array($curr) || !key_exists($columnName, $curr)){
            return array();
        }
        $temp = array();
        foreach ($targetArray as $key => $item){
            if($keepKey){
                $temp[$key] = $item[$columnName];
            }else{
                $temp[] = $item[$columnName];
            }
        }
        return $temp;
    }
    //提取多维数组中某一列数据，并根据指定列值索引，如有重复键则合并，
    public static function mergeColumnByIndexKey($data, $columnName, $indexKey,$is_merge=1){
        $firstItem = current($data);
        if(!is_array($firstItem) || !key_exists($columnName, $firstItem) || !key_exists($indexKey, $firstItem)){
            return array();
        }
        reset($data);
        $temp = array();
        foreach ($data as $item){
            if(!key_exists($item[$indexKey], $temp)){
                $temp[$item[$indexKey]] = array();
            }

            if($is_merge){
				$temp[$item[$indexKey]][] = $item[$columnName];
			}else{
				$temp[$item[$indexKey]] = $item[$columnName];
			}
        }
        return $temp;
    }

    //提取多维数组指定列且满足指定条件的数据；如果没有指定获取哪列数据则返回整个数组；否则只返回指定数据，指定数据如果不是数组则以一维数组返回，否则合并；键保留
    public static function extractColumnConditionedData($targetArray, $keyInArray, $keyColumn, $dataColumn = ""){
        if(!$targetArray)
            return array();
        $firstItem = current($targetArray);
        if(!is_array($keyInArray)){
            $keyInArray = array($keyInArray);
        }
        if(!is_array($targetArray) || !key_exists($keyColumn, $firstItem) || ($dataColumn && !key_exists($dataColumn, $firstItem))){
            return array();
        }
        $temp = array();
        foreach ($targetArray as $key => $item){
            if($keyInArray && in_array($item[$keyColumn], $keyInArray)){
                if(!$dataColumn){
                    $temp[$key] = $item;
                }else{
                    $temp[] = $item[$dataColumn];
                }
            }elseif (!$keyInArray){
                if(!$dataColumn){
                    $temp[$key] = $item;
                }else{
                    $temp[] = $item[$dataColumn];
                }
            }
        }
        return $temp;
    }
    //获取指定键数据; 键保留
    public static function arrayKeyConditioned($targetArray, $keyInArray){
        if(!is_array($keyInArray)) $keyInArray = array($keyInArray);
        $temp = array();
        foreach ($targetArray as $key => $item){
            if(in_array($key, $keyInArray)){
                $temp[$key] = $item;
            }
        }
        return $temp;
    }

    //提取多维数组指定列且键满足指定条件的数据；如果没有指定获取哪列数据则返回整个数组；否则只返回指定数据，指定数据如果不是数组则以一维数组返回，否则合并；键保留
    //todo 未考虑数据列为多维数组情况
    public static function extractKeyConditionedData($targetArray, $keyInArray, $keyColumn, $dataColumn = ""){
        $firstItem = current($targetArray);
        if(!is_array($keyInArray)){
            $keyInArray = array($keyInArray);
        }
        if(!is_array($targetArray) || !key_exists($keyColumn, $firstItem) || !($dataColumn && key_exists($dataColumn, $firstItem))){
            return array();
        }
        foreach ($targetArray as $key => $item){
            if($keyInArray && in_array($key, $keyInArray)){
                if(!$dataColumn){
                    $temp[$key] = $item;
                }else{
                    $temp[] = $item[$dataColumn];
                }
            }elseif (!$keyInArray){
                if(!$dataColumn){
                    $temp[$key] = $item;
                }else{
                    $temp[] = $item[$dataColumn];
                }
            }
        }
        return $temp;
    }

    public static function arrayJoinByKeys($originalArray, $targetArray){

    }

    //数组降维，低维信息熵不减
    public static function dimensionalityDeduction($targetArray, $conditionColumnArray, $aggregateColumnArray, $numericStay = false){
        if(!$targetArray || !current($targetArray)){
            return $targetArray;
        }
        $arrayKeys = array_keys(current($targetArray));
        foreach ($conditionColumnArray as $conditionColumn){
            if(!in_array($conditionColumn, $arrayKeys))
                return $targetArray;
        }
        foreach ($aggregateColumnArray as $aggregateColumn){
            if(!in_array($aggregateColumn, $arrayKeys))
                return $targetArray;
        }
        $temp = array();
        foreach ($targetArray as $workArray){
            $key = "";
            foreach ($conditionColumnArray as $conditionColumn){
                $key .= $workArray[$conditionColumn]."#";
            }
            if(!key_exists($key, $temp)){
                foreach ($aggregateColumnArray as $aggregateColumn){
                    if($numericStay || !is_numeric($workArray[$aggregateColumn])){
                        $workArray[$aggregateColumn] = array($workArray[$aggregateColumn]);
                    }
                }
                $temp[$key] = $workArray;
            }else{
                foreach ($aggregateColumnArray as $aggregateColumn){
                    if(!$numericStay && is_numeric($workArray[$aggregateColumn])){
                        $temp[$key][$aggregateColumn] += $workArray[$aggregateColumn];
                    }else{
                        array_push($temp[$key][$aggregateColumn], $workArray[$aggregateColumn]);
                    }
                }
            }
        }
        return $temp;
    }
    public static function array_orderby(){
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
    /**
     * @param $array
     * @param $field
     * 根据某个字段对数组进行排序
     */
    public static function sortByField($array, $field){
        $sortColumns = array();
        foreach ($array as $key => $value) {
            if (!is_array($value) || !array_key_exists($field, $value)) {
                break;
            }
            $sortColumns[$key] = $value[$field];
        }
        array_multisort($sortColumns, SORT_DESC, $array);
        return $array;
    }
    /**
     * 查找键值是value的数组
     * @param array $array 待查询的数组
     * @param string $field 键名
     * @param type $value 值
     * @return array 返回找到的元素
     * @throws Exception 格式错误抛异常
     */
    public static function findByField($array,$field,$value){
        foreach ($array as $item) {
            if(!is_array($item)||!isset($item[$field])){
                throw new SDPException("数据格式错误：内部不是数组或者没有{$field}!");
            }
            if($item[$field]==$value){
                return $item;
            }
        }
        return NULL;
    }

   public static function value($model,$attribute,$defaultValue=null)
    {
        if(is_scalar($attribute) || $attribute===null)
            foreach(explode('.',$attribute) as $name)
            {
                if(is_object($model) && isset($model->$name))
                    $model=$model->$name;
                elseif(is_array($model) && isset($model[$name]))
                    $model=$model[$name];
                else
                    return $defaultValue;
            }
        else
            return call_user_func($attribute,$model);

        return $model;
    }

   /* * @endcode
    *
    * @param array $arr 数据源
    * @param string $keyField 作为分组依据的键名
    *
    * @return array 分组后的结果
    */
   public static function groupByKey($models, $groupField,  $keyField = '')
   {
        $ret = array();
        foreach($models as $model)
        {
            $group=self::value($model,$groupField);
            if($keyField != '') {
                $value=self::value($model,$keyField);
                $ret[$group][$value]=$model;
            }
            else
                $ret[$group]=$model;
        }
       return $ret;
   }


	/**
	 * 数组分页函数  核心函数  array_slice
	 * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
	 * $count   每页多少条数据
	 * $page   当前第几页
	 * $array   查询出来的所有数组
	 * order 0 - 不变     1- 反序
	 */

	public static function page_array($count,$page,$array,$order=0){
		global $count_page; #定全局变量
		$page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
		$start=($page-1)*$count; #计算每次分页的开始位置
		if($order==1){
			$array=array_reverse($array);
		}
		$totals=count($array);
		$count_page=ceil($totals/$count); #计算总页面数
		$page_data=array();
		$page_data=array_slice($array,$start,$count);
		return $page_data;  #返回查询数据
	}

	/*
	 * 下拉统一返回
	 * $info key=value形式
	 */
	public static function getSelect($info,$default='')
	{
		if(empty($info)){
			return array();
		}
		foreach( $info as $k=>$v ){
			$result[] = [
				'id' => $k,
				'name' => $v
			];
		}
		if($default){
			$result = array_merge([['id'=>'','name'=>$default]],$result);
		}
		return $result;
	}


	/**
	 * 数组 转 对象
	 *
	 * @param array $arr 数组
	 * @return object
	 */
	public static function array_to_object($arr)
	{
		if (gettype($arr) != 'array') {
			return;
		}
		foreach ($arr as $k => $v) {
			if (gettype($v) == 'array' || getType($v) == 'object') {
				$arr[$k] = (object)self::array_to_object($v);
			}
		}

		return (object)$arr;
	}

	//根据配置剔除属性
	public static function unsetBySysConfig($result,$sys_config_key,$attr=array(),$sys_value=0)
	{
		if(empty($attr)){
			return $result;
		}
		if(SysConfigService::getInstance()->getConfigValue($sys_config_key) == $sys_value ){
			foreach( $attr as $val ){
				if(isset($result[$val])){
					unset($result[$val]);
				}
				if(array_search($val,$result)){
					unset($result[array_search($val,$result)]);
				}
			}
		}
		return $result;
	}

	/*
	 * 多维数组排序
	 * 使用 func_get_args 动态获取参数
	 * 参数1:排序数组
	 * 参数2:排序字段
	 * 参数3:排序方式
	 * 如：arr1,'id',SORT_ASC,'name',SORT_DESC
	 */
	public static function sortArrByManyField(){
		$args = func_get_args();
		if(empty($args)){
			return null;
		}
		$arr = array_shift($args);
		if(!is_array($arr)){
			throw new SDPException("第一个参数不为数组");
		}
		foreach($args as $key => $field){
			if(is_string($field)){
				$temp = array();
				foreach($arr as $index=> $val){
					$temp[$index] = $val[$field];
				}
				$args[$key] = $temp;
			}
		}
		$args[] = &$arr;//引用值
		call_user_func_array('array_multisort',$args);
		return array_pop($args);
	}

    /**
     * map数组 转 list数组
     *
     * @param $map
     * @return array
     */
    public static function mapToListArray($map)
    {
        $data = [];
        foreach ($map as $key => $value) {
            $data[] = [
                'key'   => $key,
                'value' => $value
            ];
        }
        return $data;
    }

    /**
     * 获取二维数据指定某一个字段的所有值集合，去重
     * @param $arr
     * @param $key
     * auth: 邵远鹏
     * time: 2020/8/6 2:34 下午
     * return array
     */
	public static function getArraySpecifyTheValueOfAColumn($arr, $key)
    {
        $res = [];
        foreach ($arr as $value){
            if (array_key_exists($key, $value)){
                $res[$value[$key]] = $value[$key];
            }
        }

        return $res;
    }

    /**
     * 把二维数据某一列的值作为对应数据的key
     * @param $arr
     * @param $key
     * @return array
     * auth: 邵远鹏
     * time: 2020/8/6 2:42 下午
     */
    public static function getArrayTheValueOfAColumnIsKey($arr, $key)
    {
        $res = [];
        foreach ($arr as $value){
            if (array_key_exists($key, $value)){
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }
}
