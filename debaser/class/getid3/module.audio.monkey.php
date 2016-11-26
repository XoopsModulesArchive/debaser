<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio.monkey.php                                     //
// module for analyzing Monkey's Audio files                   //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_monkey
{

	function getid3_monkey(&$fd, &$ThisFileInfo) {
		// based loosely on code from TMonkey by Jurgen Faul <jfaulØgmx*de>
		// http://jfaul.de/atl  or  http://j-faul.virtualave.net/atl/atl.html

		$ThisFileInfo['fileformat']            = 'mac';
		$ThisFileInfo['audio']['dataformat']   = 'mac';
		$ThisFileInfo['audio']['bitrate_mode'] = 'vbr';
		$ThisFileInfo['audio']['lossless']     = true;

		$ThisFileInfo['monkeys_audio']['raw'] = array();
		$thisfile_monkeysaudio                = &$ThisFileInfo['monkeys_audio'];
		$thisfile_monkeysaudio_raw            = &$thisfile_monkeysaudio['raw'];

		fseek($fd, $ThisFileInfo['avdataoffset'], SEEK_SET);
		$MACheaderData = fread($fd, 40);

		$thisfile_monkeysaudio_raw['magic'] = substr($MACheaderData, 0, 4);
		if ($thisfile_monkeysaudio_raw['magic'] != 'MAC ') {
			$ThisFileInfo['error'][] = 'Expecting "MAC" at offset '.$ThisFileInfo['avdataoffset'].', found "'.$thisfile_monkeysaudio_raw['magic'].'"';
			unset($ThisFileInfo['fileformat']);
			return false;
		}
		$thisfile_monkeysaudio_raw['nVersion']             = getid3_lib::LittleEndian2Int(substr($MACheaderData, 4, 2));
		$thisfile_monkeysaudio_raw['nCompressionLevel']    = getid3_lib::LittleEndian2Int(substr($MACheaderData, 6, 2));
		$thisfile_monkeysaudio_raw['nFormatFlags']         = getid3_lib::LittleEndian2Int(substr($MACheaderData, 8, 2));
		$thisfile_monkeysaudio_raw['nChannels']            = getid3_lib::LittleEndian2Int(substr($MACheaderData, 10, 2));
		$thisfile_monkeysaudio_raw['nSampleRate']          = getid3_lib::LittleEndian2Int(substr($MACheaderData, 12, 4));
		$thisfile_monkeysaudio_raw['nWAVHeaderBytes']      = getid3_lib::LittleEndian2Int(substr($MACheaderData, 16, 4));
		$thisfile_monkeysaudio_raw['nWAVTerminatingBytes'] = getid3_lib::LittleEndian2Int(substr($MACheaderData, 20, 4));
		$thisfile_monkeysaudio_raw['nTotalFrames']         = getid3_lib::LittleEndian2Int(substr($MACheaderData, 24, 4));
		$thisfile_monkeysaudio_raw['nFinalFrameSamples']   = getid3_lib::LittleEndian2Int(substr($MACheaderData, 28, 4));
		$thisfile_monkeysaudio_raw['nPeakLevel']           = getid3_lib::LittleEndian2Int(substr($MACheaderData, 32, 4));
		$thisfile_monkeysaudio_raw['nSeekElements']        = getid3_lib::LittleEndian2Int(substr($MACheaderData, 38, 2));

		$thisfile_monkeysaudio['flags']['8-bit']         = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0001);
		$thisfile_monkeysaudio['flags']['crc-32']        = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0002);
		$thisfile_monkeysaudio['flags']['peak_level']    = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0004);
		$thisfile_monkeysaudio['flags']['24-bit']        = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0008);
		$thisfile_monkeysaudio['flags']['seek_elements'] = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0010);
		$thisfile_monkeysaudio['flags']['no_wav_header'] = (bool) ($thisfile_monkeysaudio_raw['nFormatFlags'] & 0x0020);
		$thisfile_monkeysaudio['version']                = $thisfile_monkeysaudio_raw['nVersion'] / 1000;
		$thisfile_monkeysaudio['compression']            = $this->MonkeyCompressionLevelNameLookup($thisfile_monkeysaudio_raw['nCompressionLevel']);
		$thisfile_monkeysaudio['samples_per_frame']      = $this->MonkeySamplesPerFrame($thisfile_monkeysaudio_raw['nVersion'], $thisfile_monkeysaudio_raw['nCompressionLevel']);
		$thisfile_monkeysaudio['bits_per_sample']        = ($thisfile_monkeysaudio['flags']['24-bit'] ? 24 : ($thisfile_monkeysaudio['flags']['8-bit'] ? 8 : 16));
		if ($thisfile_monkeysaudio['bits_per_sample'] == 0) {
			$ThisFileInfo['error'][] = 'Corrupt MAC file: bits_per_sample == zero';
			return false;
		}
		$thisfile_monkeysaudio['channels']               = $thisfile_monkeysaudio_raw['nChannels'];
		$ThisFileInfo['audio']['channels']               = $thisfile_monkeysaudio['channels'];
		$thisfile_monkeysaudio['sample_rate']            = $thisfile_monkeysaudio_raw['nSampleRate'];
		if ($thisfile_monkeysaudio['sample_rate'] == 0) {
			$ThisFileInfo['error'][] = 'Corrupt MAC file: frequency == zero';
			return false;
		}
		$ThisFileInfo['audio']['sample_rate']            = $thisfile_monkeysaudio['sample_rate'];
		$thisfile_monkeysaudio['peak_level']             = $thisfile_monkeysaudio_raw['nPeakLevel'];
		$thisfile_monkeysaudio['peak_ratio']             = $thisfile_monkeysaudio['peak_level'] / pow(2, $thisfile_monkeysaudio['bits_per_sample'] - 1);
		$thisfile_monkeysaudio['frames']                 = $thisfile_monkeysaudio_raw['nTotalFrames'];
		$thisfile_monkeysaudio['samples']                = (($thisfile_monkeysaudio['frames'] - 1) * $thisfile_monkeysaudio['samples_per_frame']) + $thisfile_monkeysaudio_raw['nFinalFrameSamples'];
		$thisfile_monkeysaudio['playtime']               = $thisfile_monkeysaudio['samples'] / $thisfile_monkeysaudio['sample_rate'];
		if ($thisfile_monkeysaudio['playtime'] == 0) {
			$ThisFileInfo['error'][] = 'Corrupt MAC file: playtime == zero';
			return false;
		}
		$ThisFileInfo['playtime_seconds']                = $thisfile_monkeysaudio['playtime'];
		$thisfile_monkeysaudio['compressed_size']        = $ThisFileInfo['avdataend'] - $ThisFileInfo['avdataoffset'];
		$thisfile_monkeysaudio['uncompressed_size']      = $thisfile_monkeysaudio['samples'] * $thisfile_monkeysaudio['channels'] * ($thisfile_monkeysaudio['bits_per_sample'] / 8);
		if ($thisfile_monkeysaudio['uncompressed_size'] == 0) {
			$ThisFileInfo['error'][] = 'Corrupt MAC file: uncompressed_size == zero';
			return false;
		}
		$thisfile_monkeysaudio['compression_ratio']      = $thisfile_monkeysaudio['compressed_size'] / ($thisfile_monkeysaudio['uncompressed_size'] + $thisfile_monkeysaudio_raw['nWAVHeaderBytes']);
		$thisfile_monkeysaudio['bitrate']                = (($thisfile_monkeysaudio['samples'] * $thisfile_monkeysaudio['channels'] * $thisfile_monkeysaudio['bits_per_sample']) / $thisfile_monkeysaudio['playtime']) * $thisfile_monkeysaudio['compression_ratio'];
		$ThisFileInfo['audio']['bitrate']                = $thisfile_monkeysaudio['bitrate'];

		// add size of MAC header to avdataoffset - MD5data
		$ThisFileInfo['avdataoffset'] += 40;

		$ThisFileInfo['audio']['bits_per_sample'] = $thisfile_monkeysaudio['bits_per_sample'];
		$ThisFileInfo['audio']['encoder']         = 'MAC v'.number_format($thisfile_monkeysaudio['version'], 2);
		$ThisFileInfo['audio']['encoder_options'] = ucfirst($thisfile_monkeysaudio['compression']).' compression';

		return true;
	}

	function MonkeyCompressionLevelNameLookup($compressionlevel) {
		static $MonkeyCompressionLevelNameLookup = array(
			0     => 'unknown',
			1000  => 'fast',
			2000  => 'normal',
			3000  => 'high',
			4000  => 'extra-high',
			5000  => 'insane'
		);
		return (isset($MonkeyCompressionLevelNameLookup[$compressionlevel]) ? $MonkeyCompressionLevelNameLookup[$compressionlevel] : 'invalid');
	}

	function MonkeySamplesPerFrame($versionid, $compressionlevel) {
		if ($versionid >= 3950) {
			return 73728 * 4;
		} elseif ($versionid >= 3900) {
			return 73728;
		} elseif (($versionid >= 3800) && ($compressionlevel == 4000)) {
			return 73728;
		} else {
			return 9216;
		}
	}

}

?>