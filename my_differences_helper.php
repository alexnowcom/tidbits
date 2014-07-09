<?php
	/**
	 * Handles key=>value pairs to determine which have been added, changed or deleted.
	 * 
	 * Returns object with the properties 'add', 'update', 'delete' and optionally 'same'
	 * containing arrays of categorized data, also an int 'differences' which is the sum 
	 * of add, update and delete. Useful to write SQL for data handling. 
	 * If $result->differences = 0 there's no differences between the datasets
	 * @access	public
	 * @param	array	starting key=>value data
	 * @param	array	desired key=>value data
	 * @param	bool	should the return object contain a list of unchanged values
	 * @return object
	 */
	function differences($a1, $a2, $same = true){
	 
		$result = new stdClass();

		$result->add = array_diff_key($a2, $a1);
		$result->delete = array_diff_key($a1, $a2);

		$both = array_intersect_key($a1, $a2);
		if($same) $result->same = array();
		$result->update = array();
		foreach($both as $id=>$val){
			if($a1[$id] !== $a2[$id])
				$result->update[$id] = $a2[$id];
			elseif($same)
				$result->same[$id] = $a2[$id];
		}

		$result->differences = count($result->add) + count($result->update) + count($result->delete);

		return $result;
	}
	
	/**
	 * Turns a simple array into a key=>value multi-dimentional one 
	 * 
	 * Converts a simple array into a key=>value one with the keys matching the values
	 * It prepares an array of values into something useful for the {@link differences()} function
	 * @access	public
	 * @param	array	
	 * @return	array
	 */
	function double_array($a){

		return array_flip( array_combine($a, $a) );
		// array_flip is only a safeguard to remove any possible duplicate values
	}
	
/* * TEST : (not part of the original file, it's just here for a quick demo on what differences() does * */
  $array1 = array('1'=>'5','2'=>'5','3'=>'5','4'=>'5','5'=>'5');
  $array2 = array('1'=>'10', '2'=>'5', '3'=>'1', '4'=>'5', '6'=>'10', '7'=>'10');
	
/*
 * 1 - update +
 * 2 - same
 * 3 - update -
 * 4 - same
 * 5 - delete
 * 6 - add
 * 7 - add
 */

$test = differences($array1, $array2, true);
	
?>
<html>
	<body>
		<pre>
<?= print_r($test); ?>
		</pre>
	</body>
</html>
