<?php
// $Id: admin/menu.php,v 0.50 2004/06/30 10:00:00 frankblack Exp $
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
global $xoopsConfig;

$adminmenu[1]['title'] = _MI_DEBASER_ADMIN;
$adminmenu[1]['link'] = 'admin/index.php';
$adminmenu[2]['title'] = _MI_DEBASER_EDITGENRES;
$adminmenu[2]['link'] = 'admin/category.php';
$adminmenu[3]['title'] = _MI_DEBASER_EDITPLAYERS;
$adminmenu[3]['link'] = 'admin/index.php?op=playermanager';
$adminmenu[4]['title'] = _MI_DEBASER_SINGLEUP;
$adminmenu[4]['link'] = 'admin/upload.php?op=singleupload';
$adminmenu[5]['title'] = _MI_DEBASER_BATCH;
$adminmenu[5]['link'] = 'admin/upload.php?op=batchadd';
$adminmenu[6]['title'] = _MI_DEBASER_MAPPROVE;
$adminmenu[6]['link'] = 'admin/index.php?op=approve';
$adminmenu[7]['title'] = _MI_DEBASERRAD_ADMIN;
$adminmenu[7]['link'] = 'admin/radioindex.php';
$adminmenu[8]['title'] = _MI_DEBASER_FILETYPES;
$adminmenu[8]['link'] = 'admin/mimetypes.php';
$adminmenu[9]['title'] = _MI_DEBASER_PERMISSIONS;
$adminmenu[9]['link'] = 'admin/permissions.php';
$adminmenu[10]['title'] = _MI_DEBASER_HELP;
$adminmenu[10]['link'] = 'language/' . $xoopsConfig['language'] . '/help.php ';
