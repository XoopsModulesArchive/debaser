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

	include 'admin_header.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

	xoops_cp_header();
	debaser_adminMenu();

	echo '<fieldset style="padding-top:0px; margin-top:0px"><legend style="font-weight: bold; color: #900">'._AM_DEBASER_PERM_CPERMISSIONS.'</legend><div style="padding: 2px">';

	$cat_form = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'DebaserCatPerm', _AM_DEBASER_PERM_CSELECTPERMISSIONS );

	$result = $xoopsDB->query("SELECT genreid, subgenreid, genretitle FROM " . $xoopsDB->prefix('debaser_genre'));

	if ($xoopsDB->getRowsNum($result)) {

		while ($cat_row = $xoopsDB->fetchArray($result)) {
			$cat_form->addItem($cat_row['genreid'], $cat_row['genretitle'], $cat_row['subgenreid']);
		}

		echo $cat_form->render();
	} else {
		echo '<div><strong>'._AM_DEBASER_PERM_CNOCATEGORY.'</strong></div>';
	}

	echo '</div></fieldset>';
	unset ($cat_form);

	xoops_cp_footer();

?>