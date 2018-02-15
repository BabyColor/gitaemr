<?
//-----Action!--------
$newera = $_POST;
$action = new GoodBoi($dbpre .'layout');
$action-> GoBark ($newera);








// Signup Action
echo "<table><tr>"; //---Draw table

//-----Grouping da fields-----

$fgroups = new GoodBoi($dbpre .'layout');
$fgroups->selectmethod = 'DISTINCT group_order, group_cap';
$fgroups->GoFetch("ORDER BY group_order");
while($g=mysqli_fetch_assoc($fgroups->sq)){
	$gord=$g['group_order'];
	$groupl=$g['group_cap'];
	eval("\$groupl = \"$groupl\";");
	unset($gl); //Header draw indicator, unset in each field_group loop



	$su_field = new GoodBoi($dbpre .'layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
	$su_field-> GoFetch("WHERE form_id = 'gita_login_signup' AND group_order = '". $g['group_order'] ."' ORDER BY field_order"); //Fetch with following qury
	while($i=mysqli_fetch_assoc($su_field->sq)){
		//---------- Draw group label
		if(empty($gl)){
			$gl++;
			echo "<table><th>$groupl </td><tr> <td>";
		}


		$field_label=$i['field_label'];
		eval("\$field_label = \"$field_label\";"); //---Set field label's content as variable name for language

		$viewsonic="field_visible_view"; //-----Check which kind of view (Edit or View)

		///////////////////////////////////******* CHANGE $viewsonic=field_visible_edit" IF USER IS ADMIN

		if($input_type=='select'){ //---Decide how to draw the field based on the input_type
			$field_type='<select name='. $i['field_id'] .'>';
			$field_closure = "</select>";
			$optiontype="select";
		} elseif($input_type=='datalist') {
			$field_type="<input type=text list='". $i['field_id'] ."'><datalist id='". $i['field_id'] ."'>";
			$field_closure = "</datalist></input>";
			$optiontype="datalist";
		} else {
			$field_type='<input type='. $input_type .' name='. $i['field_id'] .'>';
			$field_closure = "</input>";
		}

		if($i[$viewsonic]>0){ // Only show if field's visible = 1
			$list_id=$i['field_list'];
			echo "
				<tr><td colspan=$i[field_label_colspan]>". $field_label ."
				<td colspan=$i[field_field_colspan]>"; //-----------Draw FIELD LABEL

			      echo $field_type;

						if($input_type=='select' or $input_type=='datalist'){ //----Draw option if the field type is select or datalist
							if(empty($i['field_list_table'])){
								$listype= "Normal List";
								$option = new GoodBoi ($dbpre .'list_list');
								$option->GoFetch("WHERE cluster='". $list_id ."'ORDER BY list_order ASC");
								$list_option='option';
								$list_value='value';
							} else {
								$listype= "Custom List";
								$option = new GoodBoi ($dbpre .$i['field_list_table']);
								$option->GoFetch("ORDER BY $i[field_order_by] ASC");
								$list_option=$i['field_list_option'];
								$list_value=$i['field_list_value'];
							}
							while($i2=mysqli_fetch_assoc($option->sq)){
								$oplabel=$i2[$list_option];
								eval("\$oplabel = \"$oplabel\";"); //---Set field label's content as variable name for language
								if($optiontype=="select"){ // Select and Datalist have different drawing elements, damned html
									echo "<option value='". $i2[$list_value] ."'>$oplabel</option>";
								} elseif ($optiontype=="datalist"){
									echo "<option value='". $i2[$list_value] ."'>$oplabel</option>";
								}
							}
						}
					echo $field_closure ." <tr>";
					unset($option);
			}
	}
	echo "</table>";
}
echo "<tr><td><input type=submit value='$lanSignUp'>";
echo "</table>";

?>
