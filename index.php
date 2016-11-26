<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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

	include 'header.php';

	$xoopsOption['template_main'] = 'debaser_index.html';

	include XOOPS_ROOT_PATH.'/header.php';

	global $xoopsModuleConfig, $xoopsModule, $xoopsUser;

	$nav_query = $xoopsDB->query("SELECT a.*, b.* FROM ".$xoopsDB->prefix('debaser_genre')." a, ".$xoopsDB->prefix('debaser_text')." b WHERE a.subgenreid = 0 AND a.genreid = b.textcatid AND b.language = $langa ORDER BY b.textcattitle ".$xoopsModuleConfig['catindex_orderby']." ");

	$hascat = $xoopsDB->getRowsNum($nav_query);

	if ($hascat < 1 && $xoopsModuleConfig['masterlang'] == $langa) redirect_header(XOOPS_URL.'/', 2, _MD_DEBASER_NOCATEGORIES);

	if ($hascat < 1 && $xoopsModuleConfig['masterlang'] != $langa) {
		$nav_query = $xoopsDB->query("SELECT a.*, b.* FROM ".$xoopsDB->prefix('debaser_genre')." a, ".$xoopsDB->prefix('debaser_text')." b WHERE a.subgenreid = 0 AND a.genreid = b.textcatid AND b.language = ".$xoopsDB->quoteString($xoopsModuleConfig['masterlang'])." ORDER BY b.textcattitle ".$xoopsModuleConfig['catindex_orderby']." ");
	}

	$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

	$tree = '';
	$depth = 1;
	$top_level_on = 1;
	$exclude = array();
	array_push($exclude, 0);

	while ($nav_row = $xoopsDB->fetchArray($nav_query)) {
		if ($gperm_handler->checkRight('DebaserCatPerm', $nav_row['textcatid'] , $groups, $module_id)) {
			$goOn = 1;
			for ($x = 0; $x < count($exclude); $x++) {
				if ($exclude[$x] == $nav_row['textcatid']) {
					$goOn = 0;
					break;
				}
			}

			if ($goOn == 1) {
				if (is_file(DEBASER_RIMG.'/category/'.$nav_row['imgurl']) && !empty($nav_row['imgurl'])) $imgurl = '<td style="width:'.$xoopsModuleConfig['shotwidth'].'px"><img src="'.DEBASER_UIMG.'/category/'.$nav_row['imgurl'].'" alt="" title="" class="catshot" /></td>';
				else $imgurl = '';

				if (!empty($nav_row['textcattext'])) $catdesc = '<td class="catdesc">'.$nav_row['textcattext'].'</td>';
				else $catdesc = '';

				$tree .= '<div class="indexdiv"><table border="0" cellpadding="0" cellspacing="0"><tr>'.$imgurl.'<td class="cattitle"><a href="genre.php?genreid='.$nav_row['textcatid'].'">'.$nav_row['textcattitle'].'</a>&nbsp;<small>['.$nav_row['total'].']</small><br />';
				array_push($exclude, $nav_row['textcatid']);
				if ($nav_row['textcatid'] < 6) $top_level_on = $nav_row['textcatid'];

				$tree .= build_child($nav_row['textcatid']).'</td>'.$catdesc.'</tr></table></div>';
			}
		}
	}

	function build_child($oldID) {

		global $exclude, $depth, $xoopsDB, $langa;
		$tempTree = '';
		$child_query = $xoopsDB->query("SELECT a.*, b.* FROM ".$xoopsDB->prefix('debaser_genre')." a, ".$xoopsDB->prefix('debaser_text')." b WHERE a.subgenreid = ".intval($oldID)." AND b.textcatsubid = ".intval($oldID)." AND b.language = $langa");

		while ($child = $xoopsDB->fetchArray($child_query)) {
			if ($child['textcatid'] != $child['textcatsubid']) {
				for ($c = 0; $c < $depth; $c++) { $tempTree .= "&nbsp;"; }

				$tempTree .= '- <small><a href="genre.php?genreid='.$child['textcatid'].'">'.$child['textcattitle'].'</a>&nbsp;['.$child['total'].']</small><br />';
				$depth++;
				$tempTree .= build_child($child['textcatid']);
				$depth--;
				array_push($exclude, $child['textcatid']);
			}
		}

		return $tempTree;
	}

	$xoopsTpl->assign('indextree', $tree);

  function getDefinedVars($varList, $excludeList)
  {
      $temp1 = array_values(array_diff(array_keys($varList), $excludeList));
      $temp2 = array();
      while (list($key, $value) = each($temp1)) {
          global $$value;
          $temp2[$value] = $$value;
      }
      return $temp2;
  }




	include_once XOOPS_ROOT_PATH.'/footer.php';

?>