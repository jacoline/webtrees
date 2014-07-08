<?php
// Allow an admin user to download the entire gedcom file.
//
// webtrees: Web based Family History software
// Copyright (C) 2014 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA

use WT\Auth;

define('WT_SCRIPT_NAME', 'admin_trees_download.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_export.php';

$controller=new WT_Controller_Page();
$controller
	->setPageTitle(WT_I18N::translate('Download GEDCOM'))
	->restrictAccess(Auth::isManager());

// Validate user parameters
$action           = WT_Filter::get('action',           'download');
$convert          = WT_Filter::get('convert',          'yes|no', 'no');
$zip              = WT_Filter::get('zip',              'yes|no', 'no');
$conv_path        = WT_Filter::get('conv_path');
$privatize_export = WT_Filter::get('privatize_export', 'none|visitor|user|gedadmin');

if ($action == 'download') {
	$exportOptions = array();
	$exportOptions['privatize'] = $privatize_export;
	$exportOptions['toANSI'] = $convert;
	$exportOptions['path'] = $conv_path;
}

$fileName = WT_GEDCOM;
if ($action == 'download' && $zip == 'yes') {
	require WT_ROOT.'library/pclzip.lib.php';

	$temppath = WT_Site::preference('INDEX_DIRECTORY') . 'tmp/';
	$zipname = 'dl' . date('YmdHis') . $fileName . '.zip';
	$zipfile = WT_Site::preference('INDEX_DIRECTORY') . $zipname;
	$gedname = $temppath . $fileName;

	$removeTempDir = false;
	if (!is_dir($temppath)) {
		$res = mkdir($temppath);
		if ($res !== true) {
			echo 'Error : Could not create temporary path!';
			exit;
		}
		$removeTempDir = true;
	}
	$gedout = fopen($gedname, 'w');
	export_gedcom($GEDCOM, $gedout, $exportOptions);
	fclose($gedout);
	$comment = 'Created by ' . WT_WEBTREES . ' ' . WT_VERSION . ' on ' . date('r') . '.';
	$archive = new PclZip($zipfile);
	$v_list = $archive->create($gedname, PCLZIP_OPT_COMMENT, $comment, PCLZIP_OPT_REMOVE_PATH, $temppath);
	if ($v_list == 0) {
		echo 'Error : ' . $archive->errorInfo(true);
	} else {
		unlink($gedname);
		if ($removeTempDir) {
			rmdir($temppath);
		}
		header('Location: ' . WT_SERVER_NAME . WT_SCRIPT_PATH . 'downloadbackup.php?fname=' . $zipname);
	}
	exit;
}

if ($action == 'download') {
	Zend_Session::writeClose();
	header('Content-Type: text/plain; charset=UTF-8');
	// We could open "php://compress.zlib" to create a .gz file or "php://compress.bzip2" to create a .bz2 file
	$gedout = fopen('php://output', 'w');
	if (strtolower(substr($fileName, -4, 4)) != '.ged') {
		$fileName .= '.ged';
	}
	header('Content-Disposition: attachment; filename="' . $fileName . '"');
	export_gedcom(WT_GEDCOM, $gedout, $exportOptions);
	fclose($gedout);
	exit;
}

$controller->pageHeader();

?>
<h2><?php echo $controller->getPageTitle(); ?> - <?php echo WT_Filter::escapeHtml(WT_GEDCOM); ?></h2>
<form class="form form-horizontal" method="GET" role="form">
	<input type="hidden" name="action" value="download">
	<input type="hidden" name="ged" value="<?php echo WT_GEDCOM; ?>">
	<div class="form-group">
		<label class="control-label col-sm-3" for="zip">
			<?php echo WT_I18N::translate('Download ZIP file'); ?>
		</label>
		<div class="col-sm-9">
			<input type="checkbox" name="zip" value="yes">
			<p class="small muted">
				<?php echo WT_I18N::translate('When you check this option, a copy of the GEDCOM file will be compressed into ZIP format before the download begins. This will reduce its size considerably, but you will need to use a compatible Unzip program (WinZIP, for example) to decompress the transmitted GEDCOM file before you can use it.<br><br>This is a useful option for downloading large GEDCOM files.  There is a risk that the download time for the uncompressed file may exceed the maximum allowed execution time, resulting in incompletely downloaded files.  The ZIP option should reduce the download time by 75 percent.'); ?>
			</p>
		</div>
	</div>
	<fieldset class="form-group">
		<legend class="control-label col-sm-3">
			<?php echo WT_I18N::translate('Apply privacy settings?'); ?>
		</legend>
		<div class="col-sm-9">
			<label for="privatize-none">
				<input checked="checked" id="privatize-none" name="privatize_export" type="radio" value="none">
				<?php echo WT_I18N::translate('None'); ?>
			</label>
			<label for="privatize-manager">
				<input id="privatize-manager" type="radio" name="privatize_export" value="gedadmin">
				<?php echo WT_I18N::translate('Manager'); ?>
			</label>
			<label for="privatize-member">
				<input id="privatize-member" type="radio" name="privatize_export" value="user">
				<?php echo WT_I18N::translate('Member'); ?>
			</label>
			<label for="privatize-visitor">
				<input id="privatize-visitor" type="radio" name="privatize_export" value="visitor">
				<?php echo WT_I18N::translate('Visitor'); ?>
			</label>
			<p class="small muted">
				<?php echo WT_I18N::translate('This option will remove private data from the downloaded GEDCOM file.  The file will be filtered according to the privacy settings that apply to each access level.  Privacy settings are specified on the GEDCOM configuration page.'); ?>
			</p>
		</div>
	</fieldset>
	<div class="form-group">
		<label class="control-label col-sm-3" for="convert">
			<?php echo WT_I18N::translate('Convert from UTF-8 to ANSI (ISO-8859-1)'); ?>
		</label>
		<div class="col-sm-9">
			<input id="convert" name="convert" type="checkbox" value="yes">
			<p class="small muted">
				<?php echo WT_I18N::translate('For optimal display on the internet, webtrees uses the UTF-8 character set.  Some programs, Family Tree Maker for example, do not support importing GEDCOM files encoded in UTF-8.  Checking this box will convert the file from <b>UTF-8</b> to <b>ANSI (ISO-8859-1)</b>.<br><br>The format you need depends on the program you use to work with your downloaded GEDCOM file.  If you aren’t sure, consult the documentation of that program.<br><br>Note that for special characters to remain unchanged, you will need to keep the file in UTF-8 and convert it to your program’s method for handling these special characters by some other means.  Consult your program’s manufacturer or author.<br><br>This <a href="http://en.wikipedia.org/wiki/UTF-8" target="_blank" title="Wikipedia article"><b>Wikipedia article</b></a> contains comprehensive information and links about UTF-8.'); ?>
			</p>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3" for="conv_path">
			<?php echo WT_I18N::translate('Add the GEDCOM media path to filenames'); ?>
		</label>
		<div class="col-sm-9">
			<input id="conv_path" name="conv_path" type="text" value="<?php echo WT_Filter::escapeHtml($GEDCOM_MEDIA_PATH); ?>">
			<p class="small muted">
				<?php echo WT_I18N::translate('Some genealogy applications create GEDCOM files that contain media filenames with full paths.  These paths will not exist on the web-server.  To allow webtrees to find the file, the first part of the path must be removed.'); ?>
			</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php echo WT_I18N::translate('continue'); ?></button>
		</div>
	</div>
</form>
