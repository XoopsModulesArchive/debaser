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

	if (!defined('XOOPS_ROOT_PATH')) die('XOOPS_ROOT_PATH unknown. Script halted!');

	define('DEBASER_ROOT', XOOPS_ROOT_PATH.'/modules/debaser');
	define('DEBASER_RINC', DEBASER_ROOT.'/include');
	define('DEBASER_RUP', DEBASER_ROOT.'/upload');
	define('DEBASER_RIMG', DEBASER_ROOT.'/images');
	define('DEBASER_CLASS', DEBASER_ROOT.'/class');
	define('DEBASER_PLAY', DEBASER_ROOT.'/playlist');

	define('DEBASER_URL', XOOPS_URL.'/modules/debaser');
	define('DEBASER_UIMG', DEBASER_URL.'/images');
	define('DEBASER_UJS', DEBASER_URL.'/js');
	define('DEBASER_UCSS', DEBASER_URL.'/css');
	define('DEBASER_UUP', DEBASER_URL.'/upload');
?>