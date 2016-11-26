<?php

class debaserTree
{
	var $table;     //table with parent-child structure
	var $id;    //name of unique id for records in table $table
	var $pid;     // name of parent id used in table $table
	var $order;    //specifies the order of query results
	var $title;     // name of a field in table $table which will be used when  selection box and paths are generated
	var $arr = array();
	var $ptrees = array();
	var $db;
	var $language;
	var $extra;
	var $permission;


	//constructor of class debaserTree
	//sets the names of table, unique id, and parend id
	// a bunch of extras to make it usable for all debasertrees
	function debaserTree($table_name, $id_name, $pid_name, $lang, $andextra, $permis)
	{
		global $groups, $module_id, $gperm_handler;
		$this->db =& Database::getInstance();
		$this->table = $table_name;
		$this->id = $id_name;
		$this->pid = $pid_name;
		$this->language = $lang;
		$this->extra = $andextra;
		$this->permission = $permis;
		$sql = "SELECT * FROM ".$this->table." ".$this->language." ".$this->extra." ORDER BY ".$this->id;
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		while ( $myrow=$this->db->fetchArray($result) ) {
			if ($this->permission == true) {
			if ($gperm_handler->checkRight('DebaserCatPerm', $myrow[$this->id], $groups, $module_id)) {
			$this->arr[$myrow[$this->id]] = $myrow;
			$this->ptrees[$myrow[$this->pid]][] = $myrow[$this->id];
			}
		 } else {
			$this->arr[$myrow[$this->id]] = $myrow;
			$this->ptrees[$myrow[$this->pid]][] = $myrow[$this->id];
		}
		}
	}

	// returns an array of first child objects for a given id($sel_id)
	function getDebaserFirstChild($sel_id, $order="")
	{
		$arr =array();
		if ( !isset($this->ptrees[$sel_id]) || count ( $this->ptrees[$sel_id] ) == 0 ) {
			return $arr;
		}
		$keys = $this->getDebaserKeys($sel_id, $order);
		foreach ( $keys as $key ) {
			$arr[] = $this->arr[$key];
		}
		return $arr;
	}

	// returns an array of all FIRST child ids of a given id($sel_id)
	function getDebaserFirstChildId($sel_id)
	{
		return $this->ptrees[$sel_id];
	}

	//returns an array of ALL child ids for a given id($sel_id)
	function getDebaserAllChildId($sel_id, $order="", $idarray = array())
	{
		if ( !isset($this->ptrees[$sel_id]) || count ( $this->ptrees[$sel_id] ) == 0 ) {
			return $idarray;
		}
		$keys = $this->getDebaserKeys($sel_id, $order);
		foreach ( $keys as $key ) {
			$idarray[] = $key;
			$idarray = $this->getDebaserAllChildId($key,$order,$idarray);
		}
		return $idarray;
	}

	//returns an array of ALL parent ids for a given id($sel_id)
	function getDebaserAllParentId($sel_id, $idarray = array())
	{
		$r_id = $this->arr[$sel_id][$this->pid];
		if ( $r_id == 0 ) {
			return $idarray;
		}
		$idarray[] = $r_id;
		$idarray = $this->getDebaserAllParentId($r_id,$idarray);
		return $idarray;
	}

	//generates path from the root id to a given id($sel_id)
	// the path is delimetered with "/"
	function getDebaserPathFromId($sel_id, $title, $path="")
	{
		$parentid = $this->arr[$sel_id][$this->pid];
		$name = $this->arr[$sel_id][$title];
		$myts =& MyTextSanitizer::getInstance();
		$name = $myts->htmlSpecialChars($name);
		$path = "/".$name.$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getDebaserPathFromId($parentid, $title, $path);
		return $path;
	}

	//makes a nicely ordered selection box
	//$preset_id is used to specify a preselected item
	//set $none to 1 to add a option with value 0
	function makeDebaserMySelBox($title, $order="", $preset_id=0, $none=0, $sel_name="", $onchange="")
	{
		if ( $sel_name == "" ) {
			$sel_name = $this->id;
		}
		$myts =& MyTextSanitizer::getInstance();
		echo "<select id='".$sel_name."' name='".$sel_name."'";
		if ( $onchange != "" ) {
			echo " onchange='".$onchange."'";
		}
		echo ">\n";
		if ( $none ) {
			echo "<option value='0'>----</option>\n";
		}
		$keys = $this->getDebaserKeys(0, $order);
		foreach ( $keys as $catid ) {
			$sel = "";
			if ( $catid == $preset_id ) {
				$sel = " selected='selected'";
			}
			echo "<option value='$catid'$sel>".$this->arr[$catid][$title]."</option>\n";
			$sel = "";
			$arr = $this->getDebaserChildTreeArray($catid, $order);
			foreach ( $arr as $option ) {
				$option['prefix'] = str_replace(".","--",$option['prefix']);
				$catpath = $option['prefix']."&nbsp;".$myts->htmlSpecialChars($option[$title]);
				if ( $option[$this->id] == $preset_id ) {
					$sel = " selected='selected'";
				}
				echo "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
				$sel = "";
			}
		}
		echo "</select>\n";
	}

	//makes a nicely ordered selection box
	//$preset_id is used to specify a preselected item
	//set $none to 1 to add a option with value 0
	function makeDebaserMyPlayerSelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="", $mime_ext)
	{
		$returnit = '';
		if ( $sel_name == "" ) {
			$sel_name = $this->id;
		}
		$myts =& MyTextSanitizer::getInstance();
		$returnit .= "<select id='".$sel_name."' name='".$sel_name."'";
		if ( $onchange != "" ) {
			$returnit .= " onchange='".$onchange."'";
		}
		$returnit .= ">\n";
		if ( $none ) {
			$returnit .= "<option value='0'>----</option>\n";
		}
		$keys = $this->getDebaserKeys(0, $order);
		foreach ( $keys as $catid ) {
			$sel = "";
			if ( $catid == $preset_id ) {
				$sel = " selected='selected'";
			}
			$returnit .= "<option value='".$mime_ext."/".$catid."'$sel>".$this->arr[$catid][$title]."</option>\n";
			$sel = "";
			$arr = $this->getDebaserChildTreeArray($catid, $order);
			foreach ( $arr as $option ) {
				$option['prefix'] = str_replace(".","--",$option['prefix']);
				$catpath = $option['prefix']."&nbsp;".$myts->htmlSpecialChars($option[$title]);
				if ( $option[$this->id] == $preset_id ) {
					$sel = " selected='selected'";
				}
				$returnit .= "<option value='".$mime_ext."/".$option[$this->id]."'$sel>$catpath</option>\n";
				$sel = "";
			}
		}
		$returnit .= "</select>\n";
		return $returnit;
	}

	//generates nicely formatted linked path from the root id to a given id
	function getDebaserNicePathFromId($sel_id, $title, $funcURL, $path="")
	{
		$parentid = $this->arr[$sel_id][$this->pid];
		$name = $this->arr[$sel_id][$title];
		$myts =& MyTextSanitizer::getInstance();
		$name = $myts->htmlSpecialChars($name);
		$path = "<a href='".$funcURL."&amp;".$this->id."=".$sel_id."'>".$name."</a> ".$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getDebaserNicePathFromId($parentid, $title, $funcURL, $path);
		return $path;
	}


	//generates id path from the root id to a given id
	// the path is delimetered with "/"
	function getDebaserIdPathFromId($sel_id, $path="")
	{
		$parentid = $this->arr[$sel_id][$this->pid];
		$path = "/".$sel_id.$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getDebaserIdPathFromId($parentid, $path);
		return $path;
	}

	function getDebaserAllChild($sel_id=0,$order="",$parray = array())
	{
		if ( !isset($this->ptrees[$sel_id]) || count ( $this->ptrees[$sel_id] ) == 0 ) {
			return $parray;
		}
		$keys = $this->getDebaserKeys($sel_id, $order);
		foreach ( $keys as $key ) {
			$parray[] = $this->arr[$key];
			$parray=$this->getDebaserAllChild($key,$order,$parray);
		}
		return $parray;
	}

	function getDebaserChildTreeArray($sel_id=0,$order="",$parray = array(),$r_prefix="")
	{
		if ( !isset($this->ptrees[$sel_id]) || count ( $this->ptrees[$sel_id] ) == 0 ) {
			return $parray;
		}
		$keys = $this->getDebaserKeys($sel_id, $order);
		foreach ( $keys as $key ) {
			$row = $this->arr[$key];
			$row['prefix'] = $r_prefix.".";
			$parray[] = $row;
			$parray = $this->getDebaserChildTreeArray($key,$order,$parray,$row['prefix']);
		}
		return $parray;
	}

	function getDebaserChildTreeLangArray($sel_id=0,$order="",$language='',$total=0,$parray = array(),$r_prefix="")
	{
		$sel_id = intval($sel_id);
		$sql = "SELECT * FROM ".$this->table." WHERE ".$this->pid."=".$sel_id." AND language = ".$language."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $parray;
		}
		while ( $row = $this->db->fetchArray($result) ) {
			$row['prefix'] = $r_prefix.".";
			array_push($parray, $row);
			$parray = $this->getDebaserChildTreeLangArray($row[$this->id],$order,$language,$total,$parray,$row['prefix']);
		}
		return $parray;
	}

	function getDebaserKeys($sel_id, $order) {
		if ( empty ( $order ) ) {
			return $this->ptrees[$sel_id];
		}
		$order = explode (" ",$order);
		if ( strtolower(end($order)) == 'desc' ) {
			array_pop ( $order );
			$func = 'desc';
		} else {
			if ( strtolower(end($order)) == 'asc' ) {
				array_pop ( $order );
			}
			$func = '';
		}
		foreach ( $this->ptrees[$sel_id] as $key ) {
			$sort[$key] = $this->arr[$key][$order[0]];
		}
			natcasesort ($sort);
		if ($func == 'desc'){
   			$sort = array_reverse($sort, true);
		}
		return array_keys ( $sort );
	}
}
?>