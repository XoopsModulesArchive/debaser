<?php
// $Id: index.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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

    include __DIR__ . '/../../mainfile.php';
    include XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';

    $GLOBALS['xoopsOption']['template_main'] = 'debaser_index.html';
    
    include XOOPS_ROOT_PATH.'/header.php';

    include_once XOOPS_ROOT_PATH.'/modules/debaser/class/debasertree.php';

    global $xoopsModuleConfig, $xoopsModule, $xoopsUser;

    $myts = MyTextSanitizer::getInstance();

    $count = 1;

    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $module_id = $xoopsModule->getVar('mid');
    $gpermHandler =  xoops_getHandler('groupperm');

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('debaser_genre') . ' WHERE subgenreid = 0 ORDER BY ' . $xoopsModuleConfig['catindex_sortby'] . ' '
                              . $xoopsModuleConfig['catindex_orderby'] . ' ');

        while ($myrow = $xoopsDB->fetchArray($result)) {
            $totaldownload = getTotalDebaserItems($myrow['genreid'], 1);

            if ($xoopsModuleConfig['usecatperm'] == 1) {
                if ($gpermHandler->checkRight('DebaserCatPerm', $myrow['genreid'], $groups, $module_id)) {
                    $title = $myts->htmlSpecialChars($myrow['genretitle']);
                    $arr = array();
                    $mytree = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid');
                    $arr = $mytree->getdebaserChildTreeArray($myrow['genreid'], 'genretitle');

                    $space = 0;
                    $subcategories = '';

                    foreach ($arr as $ele) {
                        if ($gpermHandler->checkRight('DebaserCatPerm', $ele['genreid'], $groups, $xoopsModule->getVar('mid'))) {
                            $chtitle = $myts->htmlSpecialChars($ele['genretitle']);

                            if ($space > 0) {
                                $subcategories .= '<br />';
                            }

                            $ele['prefix'] = str_replace('.', '-', $ele['prefix']);
                            $subcategories .= $ele['prefix']."&nbsp;<a href='" . XOOPS_URL . '/modules/debaser/genre.php?genreid=' . $ele['genreid'] . "'>" . $chtitle . '</a> ['
                                          . countDebaserFiles($chtitle) . ']';
                            $space++;
                        }
                    }
                }
            } else {
                $title = $myts->htmlSpecialChars($myrow['genretitle']);
                $arr = array();
                $mytree = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid');
                $arr = $mytree->getdebaserChildTreeArray($myrow['genreid'], 'genretitle');

                $space = 0;
                $subcategories = '';

                foreach ($arr as $ele) {
                    $chtitle = $myts->htmlSpecialChars($ele['genretitle']);

                    if ($space > 0) {
                        $subcategories .= '<br />';
                    }

                    $ele['prefix'] = str_replace('.', '-', $ele['prefix']);
                    $subcategories .= $ele['prefix']."&nbsp;<a href='" . XOOPS_URL . '/modules/debaser/genre.php?genreid=' . $ele['genreid'] . "'>" . $chtitle . '</a> ['
                                  . countDebaserFiles($chtitle) . ']';
                    $space++;
                }
            }
            if (is_file(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'] . '/' . $myts->htmlSpecialChars($myrow['imgurl'])) && !empty($myrow['imgurl'])) {
                if ($xoopsModuleConfig['usethumbs'] && function_exists('gd_info')) {
                    $imgurl = down_debasercreatethumb($myts->htmlSpecialChars($myrow['imgurl']), $xoopsModuleConfig['catimage'], 'thumbs', $xoopsModuleConfig['shotwidth'], $xoopsModuleConfig['shotheight'],
                        $xoopsModuleConfig['imagequality'], $xoopsModuleConfig['updatethumbs'], $xoopsModuleConfig['keepaspect']);
                } else {
                    $imgurl = XOOPS_URL . '/' . $xoopsModuleConfig['catimage'] . '/' . $myts->htmlSpecialChars($myrow['imgurl']);
                }
            } else {
                $imgurl = '';
            }
            $xoopsTpl->append('categories', array('id' => $myrow['genreid'], 'title' => $title, 'image' => $imgurl, 'subcategories' => $subcategories, 'count' => $count, 'totaldownloads' => $totaldownload['count']));
            $count++;
        }
    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/modules/debaser/debaser.css" />');


/* if (!isset($catid)){
$catid = 0;
}

echo maketree($catid,"SELECT genreid, subgenreid, genretitle FROM ".$xoopsDB->prefix('debaser_genre')." ORDER BY genretitle",0); */


        include_once XOOPS_ROOT_PATH.'/footer.php';
