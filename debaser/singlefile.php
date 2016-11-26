<?php
// $Id: singlefile.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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

    $GLOBALS['xoopsOption']['template_main'] = 'debaser_singlefile.html';

    include XOOPS_ROOT_PATH.'/header.php';

    $myts = MyTextSanitizer::getInstance();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

    $ratesong = array();

    $sql = '
	SELECT d.xfid, d.filename, d.artist, d.title, d.album, d.year, d.addinfo, d.track, d.genre, d.length, d.bitrate, d.link, d.frequence, d.rating, d.votes, d.approved, d.hits, d.views, t.genreid, t.genretitle
	FROM ' . $xoopsDB->prefix('debaser_files') . ' d, ' . $xoopsDB->prefix('debaser_genre') . ' t
	WHERE d.xfid = ' . (int)$id . '
	AND d.approved = 1
	AND d.genre = t.genretitle';

    $result = $xoopsDB->query($sql);

    list($id, $filename, $artist, $title, $album, $year, $addinfo, $track, $genre, $length, $bitrate, $link, $frequence, $rating, $votes, $approved, $hits, $views, $genreid, $genretitle) = $xoopsDB->fetchRow($result);

    $xoopsDB->queryF('
	UPDATE ' . $xoopsDB->prefix('debaser_files') . ' 
	SET views = views+1 
	WHERE xfid = ' . $id . '');

    $xoopsTpl->assign(array('id' => $id, 'filename' => $filename, 'artist' => $artist, 'title' => $title, 'album' => $album, 'year' => $year, 'addinfo' => $myts->displayTarea($addinfo, 1, 1, 1, 1, 1), 'track' => $track, 'genre' => $genre, 'length' => $length, 'bitrate' => $bitrate, 'link' => $link, 'frequence' => $frequence, 'genreid' => $genreid, 'hits' => $hits, 'views' => $views));

        if ($rating != 0.0000) {
            $ratesong['rating'] = '' . _MD_DEBASER_RATING . ': ' . $myts->stripSlashesGPC(number_format($rating, 2));
            $ratesong['votes'] = '' . _MD_DEBASER_VOTES . ': ' . $myts->stripSlashesGPC($votes);
        } else {
            $ratesong['rating'] = _MD_DEBASER_NOTRATED;
        }

        if ($xoopsUser) {
            $xoopsModule = XoopsModule::getByDirname('debaser');

            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $xoopsTpl->assign('isxadmin', true);
            }
        }
        $xoopsTpl->assign('lang_comments', _COMMENTS);
        $xoopsTpl->assign('ratesong', $ratesong);

        if ($xoopsModuleConfig['guestvote'] == 1) {
            $xoopsTpl->assign('guestvote', true);
        }
        if ($xoopsUser) {
            $xoopsTpl->assign('guestvote', true);
        }

    /* determine if downloads are allowed or if download is a link */
        if ($xoopsModuleConfig['debaserallowdown'] == 1) {
            $xoopsTpl->assign('allowyes', true);
        }

    include XOOPS_ROOT_PATH.'/include/comment_view.php';

    include_once XOOPS_ROOT_PATH.'/footer.php';
