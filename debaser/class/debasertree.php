<?php
// $Id: xoopstree.php,v 1.7 2004/06/14 14:22:12 skalpa Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

class debaserTree
{
	var $table;     //table with parent-child structure
	var $id;    //name of unique id for records in table $table
	var $pid;     // name of parent id used in table $table
	var $order;    //specifies the order of query results
	var $title;     // name of a field in table $table which will be used when  selection box and paths are generated
	var $db;

	//constructor of class XoopsTree
	//sets the names of table, unique id, and parend id
	function debaserTree($table_name, $id_name, $pid_name)
	{
		$this->db =& Database::getInstance();
		$this->table = $table_name;
		$this->id = $id_name;
		$this->pid = $pid_name;
	}


	// returns an array of first child objects for a given id($sel_id)
	function getdebaserFirstChild($sel_id, $order="")
	{
		$arr =array();
		$sql = "SELECT * FROM ".$this->table." WHERE ".$this->pid."=".$sel_id."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count==0 ) {
			return $arr;
		}
		while ( $myrow=$this->db->fetchArray($result) ) {
			array_push($arr, $myrow);
		}
		return $arr;
	}

	// returns an array of all FIRST child ids of a given id($sel_id)
	function getdebaserFirstChildId($sel_id)
	{
		$idarray =array();
		$result = $this->db->query("SELECT ".$this->id." FROM ".$this->table." WHERE ".$this->pid."=".$sel_id."");
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $idarray;
		}
		while ( list($id) = $this->db->fetchRow($result) ) {
			array_push($idarray, $id);
		}
		return $idarray;
	}

	//returns an array of ALL child ids for a given id($sel_id)
	function getdebaserAllChildId($sel_id, $order="", $idarray = array())
	{
		$sql = "SELECT ".$this->id." FROM ".$this->table." WHERE ".$this->pid."=".$sel_id."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result=$this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count==0 ) {
			return $idarray;
		}
		while ( list($r_id) = $this->db->fetchRow($result) ) {
			array_push($idarray, $r_id);
			$idarray = $this->getdebaserAllChildId($r_id,$order,$idarray);
		}
		return $idarray;
	}

	//returns an array of ALL parent ids for a given id($sel_id)
	function getdebaserAllParentId($sel_id, $order="", $idarray = array())
	{
		$sql = "SELECT ".$this->pid." FROM ".$this->table." WHERE ".$this->id."=".$sel_id."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result=$this->db->query($sql);
		list($r_id) = $this->db->fetchRow($result);
		if ( $r_id == 0 ) {
			return $idarray;
		}
		array_push($idarray, $r_id);
		$idarray = $this->getdebaserAllParentId($r_id,$order,$idarray);
		return $idarray;
	}

	//generates path from the root id to a given id($sel_id)
	// the path is delimetered with "/"
	function getdebaserPathFromId($sel_id, $title, $path="")
	{
		$result = $this->db->query("SELECT ".$this->pid.", ".$title." FROM ".$this->table." WHERE ".$this->id."=$sel_id");
		if ( $this->db->getRowsNum($result) == 0 ) {
			return $path;
		}
		list($parentid,$name) = $this->db->fetchRow($result);
		$myts =& MyTextSanitizer::getInstance();
		$name = $myts->makeTboxData4Show($name);
		$path = "/".$name.$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getdebaserPathFromId($parentid, $title, $path);
		return $path;
	}

	//makes a nicely ordered selection box
	//$preset_id is used to specify a preselected item
	//set $none to 1 to add a option with value 0
	function debaserSelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
	{
		if ( $sel_name == "" ) {
			$sel_name = $this->id;
		}
		$myts =& MyTextSanitizer::getInstance();
		echo "<select name='".$sel_name."'";
		if ( $onchange != "" ) {
			echo " onchange='".$onchange."'";
		}
		echo ">\n";
		$sql = "SELECT ".$this->id.", ".$title." FROM ".$this->table." WHERE ".$this->pid."=0";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		if ( $none ) {
			echo "<option value='0'>----</option>\n";
		}
		while ( list($catid, $name) = $this->db->fetchRow($result) ) {
			$sel = "";
			if ( $catid == $preset_id ) {
				$sel = " selected='selected'";
			}
			echo "<option value='$catid'$sel>$name</option>\n";
			$sel = "";
			$arr = $this->getdebaserChildTreeArray($catid, $order);
			foreach ( $arr as $option ) {
				$option['prefix'] = str_replace(".","--",$option['prefix']);
				$catpath = $option['prefix']."&nbsp;".$myts->makeTboxData4Show($option[$title]);
				if ( $option[$this->id] == $preset_id ) {
					$sel = " selected='selected'";
				}
				echo "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
				$sel = "";
			}
		}
		echo "</select>\n";
	}

	//generates nicely formatted linked path from the root id to a given id
	function getdebaserNicePathFromId($sel_id, $title, $funcURL, $path="")
	{
		$sql = "SELECT ".$this->pid.", ".$title." FROM ".$this->table." WHERE ".$this->id."=$sel_id";
		$result = $this->db->query($sql);
		if ( $this->db->getRowsNum($result) == 0 ) {
			return $path;
		}
		list($parentid,$name) = $this->db->fetchRow($result);
		$myts =& MyTextSanitizer::getInstance();
		$name = $myts->makeTboxData4Show($name);
		$path = "<a href='".$funcURL."&".$this->id."=".$sel_id."'>".$name."</a>&nbsp;:&nbsp;".$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getdebaserNicePathFromId($parentid, $title, $funcURL, $path);
		return $path;
	}

	//generates id path from the root id to a given id
	// the path is delimetered with "/"
	function getdebaserIdPathFromId($sel_id, $path="")
	{
		$result = $this->db->query("SELECT ".$this->pid." FROM ".$this->table." WHERE ".$this->id."=$sel_id");
		if ( $this->db->getRowsNum($result) == 0 ) {
			return $path;
		}
		list($parentid) = $this->db->fetchRow($result);
		$path = "/".$sel_id.$path."";
		if ( $parentid == 0 ) {
			return $path;
		}
		$path = $this->getdebaserIdPathFromId($parentid, $path);
		return $path;
	}

	function getdebaserAllChild($sel_id=0,$order="",$parray = array())
	{
		$sql = "SELECT * FROM ".$this->table." WHERE ".$this->pid."=".$sel_id."";
		if ( $order != "" ) {
			$sql .= " ORDER BY $order";
		}
		$result = $this->db->query($sql);
		$count = $this->db->getRowsNum($result);
		if ( $count == 0 ) {
			return $parray;
		}
		while ( $row = $this->db->fetchArray($result) ) {
			array_push($parray, $row);
			$parray=$this->getdebaserAllChild($row[$this->id],$order,$parray);
		}
		return $parray;
	}

	function getdebaserChildTreeArray($sel_id=0,$order="",$parray = array(),$r_prefix="")
	{
	/* angry variable because if values are strings the sql-query is broken */
	$fuck = "'";
	/* end of angry variable */
	
		$sql = "SELECT * FROM ".$this->table." WHERE ".$this->pid." = $fuck ".$sel_id." $fuck ";
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
			$parray = $this->getdebaserChildTreeArray($row[$this->id],$order,$parray,$row['prefix']);
		}
		return $parray;
	}
}
?>