<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.misc.exe.php                                         //
// module for analyzing EXE files                              //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_exe
{

	function getid3_exe(&$fd, &$ThisFileInfo) {

		$ThisFileInfo['fileformat'] = 'exe';

		$ThisFileInfo['error'][] = 'EXE parsing not enabled in this version of getID3()';
		return false;

	}

}


?>