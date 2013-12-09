<?php
class App_Util_Array
{
	static function groupByFather ($array, $id = "id", $father = "fatherId", &$result)
	{
		$huerfanos=array();
		$newArray = $result;
		$flagId = false;
		if (is_array($array)) {
			$theArray=array();
			foreach ($array as $key => $subArray) {
				$subArray=(array)$subArray;
				$newKey = $subArray[$id];
				if (! key_exists($id, $subArray)) {
					$keysArray = array_keys($subArray);
					$id = $keysArray[0];
				}
				if (!empty($subArray[$father]))  $newKey = (($subArray[$father]*1000) +$key); 
				$theArray[$newKey]=$subArray;
			}
			
			ksort($theArray);
			//if(App_User::getUserId()==2) {Zend_Debug::dump($theArray,"El array");}
			$array=$theArray;
			foreach ($array as $key => $subArray) {
				if (! key_exists($id, $subArray)) {
					$keysArray = array_keys($subArray);
					$id = $keysArray[0];
					//Zend_Debug::dump($subArray);
					//Zend_Debug::dump($id);
				}
				if (is_object($subArray))
				$subArray = (array) $subArray;
				if (empty($subArray[$father])) {
					$newArray[$subArray[$id]] = $subArray;
					$newArray[$subArray[$id]]["child"] = array();
				} else {
					if (isset($newArray[$subArray[$father]])) {
						$newArray[$subArray[$father]]["child"][$subArray[$id]] = $subArray;
						$newArray[$subArray[$father]]["child"][$subArray[$id]]["child"] = array();
					} else {
						$findSuccess = false;
						foreach ($newArray as $subkey => $subvalue) {
							if (isset($subvalue["child"][$subArray[$father]])) {
								$newArray[$subkey]["child"][$subArray[$father]]["child"][$subArray[$id]] = $subArray;
								$newArray[$subkey]["child"][$subArray[$father]]["child"][$subArray[$id]]["child"] = array();
								$findSuccess = true;
							}
						}
						if (! $findSuccess) {
							$findSuccess = false;
							foreach ($newArray as $subkey => $subvalue) {
								foreach ($subvalue["child"] as $subkey2 => $subvalue2) {
									if (isset(
									$subvalue2["child"][$subArray[$father]])) {
										$newArray[$subkey]["child"][$subkey2]["child"][$subArray[$father]]["child"][$subArray[$id]] = $subArray;
										$newArray[$subkey]["child"][$subkey2]["child"][$subArray[$father]]["child"][$subArray[$id]]["child"] = array();
										$findSuccess = true;
									}
								}
							}
							if (! $findSuccess) {
								
								//TODO: mperez, sugerir funcion recursiva para hijos del cuarto nivel en adelante
							}
						}
						
						/*if (! $findSuccess) {
						
							$huerfanos[$subArray[$id]] = $subArray;
							$huerfanos[$subArray[$id]]["child"] = array();
						}*/
					}
				}
			}
			//if(App_User::getUserId()==2) {Zend_Debug::dump($huerfanos,"los herfanos");}
			$result = $newArray;
		} else {
			$result = $array;
		}
	}
	static function arrayColumns ($columns, $columnsPermits = array())
	{
		$permits = App_Util_DomVal::getArrayDomVal("permitFields", false, false);
		$defaultPermit = array();
		foreach ($permits as $key => $value) {
			$arrayPermits[$key] = $value;
			$defaultPermit[$value] = false;
		}
		$arrayResult = array();
		$cont = 0;
                //Zend_Debug::dump($columns);
		foreach ($columns as $keyColumn => $valueColumn) {
                        $cont ++;
			$pos = $cont;
			$arrayColumnPermits = $defaultPermit;
			$dependency = false;
			$dependenceField = "";
			$operatorDependence = false;
			$visible = false;
			$listPermits = "," .
			$columnsPermits[$valueColumn["COLUMN_NAME"]]->domVal_permitFields .
             ",";
			if ($columnsPermits[$valueColumn["COLUMN_NAME"]]->visible) {
				foreach ($arrayPermits as $key => $value) {
					$arrayColumnPermits[$value] = false;
					$keySearch = "," . $key . ",";
					if (strpos($listPermits, $keySearch) !== false) {
						$arrayColumnPermits[$value] = true;
						$visible = true;
					}
				}
			}
			$pos = $columnsPermits[$valueColumn["COLUMN_NAME"]]->fieMod_id;
			$visible = $columnsPermits[$valueColumn["COLUMN_NAME"]]->visible;
			if ($columnsPermits[$valueColumn["COLUMN_NAME"]]->fieMod_dependence !=
			0 && $visible) {
				$dependency = true;
				$dependenceField = $columnsPermits[$valueColumn["COLUMN_NAME"]]->fieMod_dependenceField .
                 "_" .
				$columnsPermits[$valueColumn["COLUMN_NAME"]]->fieMod_dependence;
				$operatorDependence  = $columnsPermits[$valueColumn["COLUMN_NAME"]]->domVal_comparedOperators;
				if(!is_null($operatorDependence) && $operatorDependence>0){
					$operatorDependence = App_Util_DomVal::getValueDomVal($operatorDependence,true);
				}
			}
			$arrayResult["0_{$pos}"] = array(
            "name" => $valueColumn["COLUMN_NAME"], "id" => $pos, "type" => 0, 
            "draw" => $visible, "metadata" => $valueColumn, 
            "permits" => $arrayColumnPermits, "dependency" => $dependency, 
            "dependenceField" => $dependenceField, 
            "operatorDependence" =>$operatorDependence,
            "order" => 100 + $cont, 
            "childs" => array());
		}
		return $arrayResult;
	}
	static function arrayAditionalData ($columns, $level = 0,
	$columnsPermits = array(), &$cont = 0, $isArray = 0)
	{
		$permits = App_Util_DomVal::getArrayDomVal("permitFields", false, false);
		$defaultPermit = array();
		foreach ($permits as $key => $value) {
			$arrayPermits[$key] = $value;
			$defaultPermit[$value] = false;
		}
		$arrayResult = array();
		$arrayColumns = array();
		if ($level == 0) {
			self::groupByFather($columns, "adiDat_id", "adiDat_father",
			$arrayColumns);
		} else {
			$arrayColumns = $columns;
		}
		foreach ($arrayColumns as $keyColumn => $valueColumn) {
                    //Zend_Debug::dump('valueColumn');
					//if(App_User::getUserId()==2) {Zend_Debug::dump($arrayAditionalData);die;}
                    if ($valueColumn['adiDat_isArray'] != 1 || $isArray != 1){
			$cont ++;
			$arrayColumnPermits = $defaultPermit;
			$dependency = false;
			$dependenceField = "";
			$visible = false;
			$operatorDependence = false;
			if (isset($columnsPermits[$valueColumn["adiDat_id"]])) {
				$listPermits = "," .
				$columnsPermits[$valueColumn["adiDat_id"]]->domVal_permitFields .
                 ",";
				if ($columnsPermits[$valueColumn["adiDat_id"]]->visible) {
					foreach ($arrayPermits as $key => $value) {
						$keySearch = "," . $key . ",";
						if (strpos($listPermits, $keySearch) !== false) {
							$arrayColumnPermits[$value] = true;
							$visible = true;
						}
					}
				}
				$visible = $columnsPermits[$valueColumn["adiDat_id"]]->visible;
				if ($columnsPermits[$valueColumn["adiDat_id"]]->fieMod_dependence !=
				0 && $visible) {
					$dependency = true;
					$dependenceField = $columnsPermits[$valueColumn["adiDat_id"]]->fieMod_dependenceField .
                     "_" .
					$columnsPermits[$valueColumn["adiDat_id"]]->fieMod_dependence;
					$operatorDependence  = $columnsPermits[$valueColumn["adiDat_id"]]->domVal_comparedOperators;
					if(!is_null($operatorDependence) && $operatorDependence>0){
						$operatorDependence = App_Util_DomVal::getValueDomVal($operatorDependence,true);
					}
				}
			}
			$arrayResult["1_{$keyColumn}"] = array(
            "name" => $valueColumn["adiDat_name"], 
            "id" => $valueColumn["adiDat_id"], "type" => 1, 
            "id_adiDatCom" => $valueColumn["domVal_adiDat"], "draw" => $visible, 
            "metadata" => self::formatAditionalDataColumn($valueColumn), 
            "permits" => $arrayColumnPermits, "dependency" => $dependency, 
            "dependenceField" => $dependenceField, "order" => 200 + $cont, 
            "operatorDependence" =>$operatorDependence,
            "childs" => self::arrayAditionalData($valueColumn["child"], 
			$level + 1, $columnsPermits, $cont, $isArray));
                    }   // Fin if ($valueColumn['adiDat_isArray'] != 1 || $isArray != 1)
                    else{
                        unset($arrayResult["1_{$keyColumn}"]);
                    }
		}
                //Zend_Debug::dump($arrayResult);
                return $arrayResult;
	}
	//FIXME: revisar funcion
	static function arrayAditionalDataInLine ($arrayColumns, $isArray = 0)
	{
		$arrayResult = array();
		foreach ($arrayColumns as $key => $value) {
                   if ($value['metadata']["isArray"] != 1 || $isArray != 1){ 
			$arrayResult[$key] = $value;
			$arrayChilds = array();
			if (count($value["childs"]) > 0) { //&& $value["metadata"]["isArray"] != 1
				$arrayChilds = self::arrayAditionalDataInLine($value["childs"], $isArray);
			}
			$value["childs"] = array();
			$arrayResult[$key] = $value;
			$arrayResult = array_merge($arrayResult, $arrayChilds);
                   }
		}
		return $arrayResult;
	}
	static private function formatAditionalDataColumn ($column)
	{
		$type = self::typeAdiDat($column["domVal_dataType"]);
		$arrayDefaul = array("SCHEMA_NAME" => 'ADIDAT',
        "TABLE_NAME" => "MAIN_aditionalDataDO", 
        "COLUMN_NAME" => "adiDatDO_value", "COLUMN_POSITION" => 2, 
        "DATA_TYPE" => $type["type"], //
"DEFAULT" => NULL, "NULLABLE" => true,  //
        "LENGTH" => $type["lenght"], //
"SCALE" => NULL, "PRECISION" => 10, 
        "UNSIGNED" => NULL, "PRIMARY" => false, //
"PRIMARY_POSITION" => NULL, 
        "IDENTITY" => false, "AliasTable" => "adiDatDO", //
"adiDat" => $column, 
        "AliasColumn" => $column["adiDat_name"], 
        "fatherId" => $column["adiDat_father"], 
        "typeAdiDat" => $column["domVal_dataType"], 
        "isArray" => $column["adiDat_isArray"]);//

		return $arrayDefaul;
	}
	static private function typeAdiDat ($type)
	{
		$typeDom = "group";
		if (! is_null($type))
		$typeDom = App_Util_DomVal::getValueDomVal($type, true);
		$type = array("type" => $typeDom, "lenght" => "50");
		switch ($typeDom) {
			case "currency":
				break;
			case "date":
				$type["type"] = "date";
				$type["lenght"] = 16;
				break;
			case "time":
				$type["type"] = "date";
				$type["lenght"] = 16;
				break;
			case "dateTime":
				$type["type"] = "datetime";
				$type["lenght"] = 16;
				break;
			case "float":
				break;
			case "sequential":
				break;
			case "select":
				break;
			case "text":
				$type["type"] = "varchar";
				$type["lenght"] = 254;
				break;
			case "longText":
				$type["type"] = "varchar";
				$type["lenght"] = 2000;
				break;
			case "integer":
				$type["type"] = "int";
				$type["lenght"] = 4;
				break;
			case "boolean":
				$type["type"] = "bit";
				$type["lenght"] = 1;
				break;
			case "group":
				break;
		}
		return $type;
	}
	/**
	 *Checks recursive if the given key or index exists in the array
	 * @param mixed $needle Value to check.
	 * @param array $haystack An array with keys to check.
	 * @return boolean Returns true on success or false on failure.
	 */
	static function array_key_exists_r ($needle, $haystack)
	{
		$result = array_key_exists($needle, $haystack);
		if ($result)
		return $result;
		foreach ($haystack as $v) {
			if (is_array($v)) {
				$result = App_Util_Array::array_key_exists_r($needle, $v);
			}
			if ($result)
			return $result;
		}
		return $result;
	}
	/**
	 *Searches the array for a given value and returns the corresponding key if successful
	 * @param array $haystack The array.
	 * @param type $needle The searched value.If needle is a string, the comparison is done in a case-sensitive manner.
	 * @param mixed $index Index to filter in the array
	 * @return mixed  the key for needle if it is found in the array, false otherwise.
	 */
	static function array_search_r ($haystack, $needle, $index = null)
	{
		$aIt = new RecursiveArrayIterator($haystack);
		$it = new RecursiveIteratorIterator($aIt);
		while ($it->valid()) {
			if (((isset($index) and ($it->key() == $index)) or (! isset($index))) and
			($it->current() == $needle)) {
				return $aIt->key();
			}
			$it->next();
		}
		return false;
	}
	/**
	 *
	 * Sort array by field
	 * @param array $data
	 * @param array $field
	 */
	static function sortArray ($data, $field)
	{
		if (! is_array($field)) {
			$field = array($field);
		}
		uasort($data,
		function  ($a, $b) use( $field)
		{
			$retval = 0;
			foreach ($field as $fieldname) {
				if ($retval == 0) {
					$retval = strnatcmp($a[$fieldname], $b[$fieldname]);
				}
			}
			return $retval;
		});
		return $data;
	}
	/**
	 * Get all values from specific key in a multidimensional array
	 *
	 * @param $key string
	 * @param $arr array
	 * @return null|string|array
	 *
	 * Example:
	 * $arr = array(
	 'foo' => 'foo',
	 'bar' => array(
	 'baz' => 'baz',
	 'candy' => 'candy',
	 'vegetable' => array(
	 'carrot' => 'carrot',
	 )
	 ),
	 'vegetable' => array(
	 'carrot' => 'carrot2',
	 ),
	 'fruits' => 'fruits',
	 );
	 var_dump(array_value_recursive('carrot', $arr)); // array(2) { [0]=> string(6) "carrot" [1]=> string(7) "carrot2" }
	 var_dump(array_value_recursive('apple', $arr)); // null
	 var_dump(array_value_recursive('baz', $arr)); // string(3) "baz"
	 var_dump(array_value_recursive('candy', $arr)); // string(5) "candy"
	 var_dump(array_value_recursive('pear', $arr)); // null
	 */
	static function array_value_recursive ($key, array $arr)
	{
		$val = array();
		array_walk_recursive($arr,
		function  ($v, $k) use( $key, &$val)
		{
			if ($k == $key){
				 
				array_push($val, $v);
			}
		});
		return count($val) > 1 ? $val : array_pop($val);
	}
	/**
	 * Sort Array by another array
	 *
	 * @param $array array to order
	 * @param $orderArray array with order keys
	 * @return array ordered with only the index in $orderArray
	 *
	 * Example:
	 * $customer['address'] = '123 fake st';
	 $customer['name'] = 'Tim';
	 $customer['dob'] = '12/08/1986';
	 $customer['dontSortMe'] = 'this value doesnt need to be sorted';
	 $properOrderedArray = sortArrayByArray($customer, array('name', 'dob', 'address'));
	 */
	static function sortArrayByArray ($array, $orderArray,$intersected=true)
	{
		if($intersected){
			$array = array_intersect_key($array, array_flip($orderArray));
		}
		$ordered = array();
		foreach ($orderArray as $key) {
			if (array_key_exists($key, $array)) {
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}
		return $ordered + $array;
	}
	/**
	 * Convierte un objeto a un arreglo de manera recursiva, convirtiendo todos los objetos en Arreglos
	 *
	 * @param mixed $object Object to convert
	 * @return array
	 */
	static function object2Array_recursive($object) {
		if (is_object ( $object )){
			$object = get_object_vars ( $object );
		}
		if (is_array ( $object )){
			foreach ( $object as $key => $value ){
				$object [$key] = App_Util_Array::object2Array_recursive ($object [$key]);
			}
		}
		return $object;
	}
        
        
        
        
        
        /**
         * 
         * @param type $array
         * @return type
         */
        static function array_utf8_decode($array){
            $result = array();
            foreach($array as $a){
                $result[] = array_map('utf8_decode', $a);                
            }
            return $result;
        }
        /**
         * 
         * @param type $array
         * @return type
         */
        static function array_utf8_encode($array){
            $result = array();
            foreach($array as $a){
                $result[] = array_map('utf8_encode', $a);                
            }
            return $result;
        }
}
