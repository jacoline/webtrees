<?php
// Header for webtrees administration theme
//
// webtrees: Web based Family History software
// Copyright (C) 2014 webtrees development team.
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

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// This theme uses the jQuery “colorbox” plugin to display images
$this
	->addExternalJavascript(WT_JQUERY_COLORBOX_URL)
	->addExternalJavascript(WT_JQUERY_WHEELZOOM_URL)
	->addInlineJavascript('activate_colorbox();')
	->addInlineJavascript('jQuery.extend(jQuery.colorbox.settings, {width:"75%", height:"75%", transition:"none", slideshowStart:"'. WT_I18N::translate('Play').'", slideshowStop:"'. WT_I18N::translate('Stop').'"})')
	->addInlineJavascript('
		jQuery.extend(jQuery.colorbox.settings, {
			title: function() {
				var img_title = jQuery(this).data("title");
				return img_title;
			}
		});
	');

?>
<!DOCTYPE html>
<html <?php echo WT_I18N::html_markup(); ?>>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="robots" content="noindex,nofollow">
		<title><?php echo WT_Filter::escapeHtml($title); ?></title>
		<link rel="icon" href="<?php echo WT_CSS_URL; ?>favicon.png" type="image/png">

		<link rel="stylesheet" href="<?php echo WT_BOOTSTRAP_CSS_URL; ?>" type="text/css">
		<link rel="stylesheet" href="<?php echo WT_BOOTSTRAP_ACCESSIBILITY_CSS_URL; ?>" type="text/css">
		<link rel="stylesheet" href="<?php echo WT_DATATABLES_BOOTSTRAP_CSS_URL; ?>" type="text/css">
		<?php if ($TEXT_DIRECTION === 'rtl'): ?>
		<link rel="stylesheet" href="<?php echo WT_BOOTSTRAP_RTL_CSS_URL; ?>" type="text/css">
		<?php endif; ?>
		<link rel="stylesheet" href="<?php echo WT_FONT_AWESOME_CSS_URL; ?>" type="text/css">
		<link rel="stylesheet" href="<?php echo WT_CSS_URL; ?>style.css" type="text/css">

		<?php echo $javascript; ?>
		<script src="<?php echo WT_JQUERY_URL; ?>"></script>
		<script src="<?php echo WT_BOOTSTRAP_JS_URL; ?>"></script>
		<script src="<?php echo WT_BOOTSTRAP_ACCESSIBILITY_JS_URL; ?>"></script>
		<script src="<?php echo WT_DATATABLES_JS_URL; ?>"></script>
		<script src="<?php echo WT_DATATABLES_BOOTSTRAP_JS_URL; ?>"></script>
		<style>
			legend.control-label {
				border: inherit;
				font-weight: 700;
				font-size: inherit;
			}
		</style>
	</head>

	<body class="container">
		<a class="btn btn-primary sr-only sr-only-focusable" href="#content" style="position: absolute;">
			<?php echo /* I18N: Scroll past the navigation menus to the main part of the page */ WT_I18N::translate('Skip to content'); ?>
		</a>
		<header class="row">
			<div class="col-xs-12">
				<h1 class="text-center">
					<?php echo WT_I18N::translate('Administration'); ?>
					—
					<?php echo WT_WEBTREES, ' ', WT_VERSION; ?>
				</h1>
			</div>
		</header>

		<nav class=row">
			<ul class="nav nav-tabs" role="tablist">
				<!-- ADMINISTRATION -->
				<li class="dropdown<?php echo preg_match('/^admin(_site|.php)/', WT_SCRIPT_NAME) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-cogs"></i>
						<?php echo /* I18N: Menu entry */ WT_I18N::translate('Administration'); ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation">
							<a role="menuitem" href="admin_site_config.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Site configuration'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_site_logs.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Logs'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_site_readme.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('README documentation'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_site_info.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('PHP information'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_site_access.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Site access rules'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_site_clean.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Clean up data folder'); ?>
							</a>
						</li>
					</ul>
				</li>
				<!-- FAMILY TREES -->
				<li<?php echo preg_match('/^admin_trees/', WT_SCRIPT_NAME) ? ' class="active"' : ''; ?>>
					<a href="admin_trees_manage.php">
						<i class="fa fa-leaf"></i>
						<?php echo /* I18N: Menu entry */ WT_I18N::translate('Family trees'); ?>
					</a>
				</li>
				<!-- USERS -->
				<li class="dropdown<?php echo preg_match('/^admin_users/', WT_SCRIPT_NAME) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-user"></i>
						<?php echo /* I18N: Menu entry */ WT_I18N::translate('Users'); ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation">
							<a role="menuitem" href="admin_users.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('User administration'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_users.php?action=createform">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Add a new user'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_users_bulk.php">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Send broadcast messages'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_users.php?action=cleanup">
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Delete inactive users'); ?>
							</a>
						</li>
						<li role="presentation">
							<a
								role="menuitem"
								href="index_edit.php?gedcom_id=-1"
								onclick="return modalDialog('index_edit.php?gedcom_id=-1', '<?php echo WT_I18N::translate('Set the default blocks for new family trees'); ?>');"
								>
								<?php echo /* I18N: Menu entry */ WT_I18N::translate('Set the default blocks'); ?>
							</a>
						</li>
					</ul>
				</li>
				<!-- MEDIA -->
				<li class="dropdown<?php echo preg_match('/^admin_media/', WT_SCRIPT_NAME) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-photo"></i>
						<?php echo /* I18N: Menu item */ WT_I18N::translate('Media'); ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation">
							<a role="menuitem" href="admin_media.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Manage media'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_media_upload.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Upload media files'); ?>
							</a>
						</li>
					</ul>
				</li>
				<!-- MODULES -->
				<li class="dropdown<?php echo preg_match('/module/', WT_SCRIPT_NAME) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-th-large"></i>
						<?php echo /* I18N: Menu item */ WT_I18N::translate('Modules'); ?>
						<span class="caret"></span>
					</a>
					<ul
						class="dropdown-menu"
						role="menu"
					>
						<li role="presentation">
							<a role="menuitem" href="admin_modules.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Module administration'); ?>
							</a>
						</li>
						<li role="presentation" class="divider"></li>
						<li role="presentation">
							<a role="menuitem" href="admin_module_menus.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Menus'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_module_tabs.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Tabs'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_module_blocks.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Blocks'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_module_sidebar.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Sidebar'); ?>
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" href="admin_module_reports.php">
								<?php echo /* I18N: Menu item */ WT_I18N::translate('Reports'); ?>
							</a>
						</li>
						<li role="presentation" class="divider"></li>
						<?php foreach (WT_Module::getActiveModules(true) as $module) { ?>
							<?php if ($module instanceof WT_Module_Config) { ?>
								<li role="presentation">
									<a role="menuitem" href="<?php echo $module->getConfigLink(); ?>">
										<i><?php echo WT_Filter::escapeHtml($module->getTitle()); ?></i>
									</a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</li>
				<!-- PENDING CHANGES -->
				<?php if (WT_USER_CAN_ACCEPT && exists_pending_change()) { ?>
				<li>
					<a href="#" class="text-danger" onclick="window.open('edit_changes.php', '_blank', chan_window_specs); return false;">
						<i class="fa fa-random"></i>
						<?php echo /* I18N: Menu item */ WT_I18N::translate('Pending changes'); ?>
					</a>
				</li>
				<?php } ?>
				<!-- LANGUAGES -->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-comments-o"></i>
						<?php echo WT_I18N::translate('Language'); ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<?php if (WT_I18N::installed_languages()) { ?>
						<?php foreach (WT_I18N::installed_languages() as $lang => $language) : ?>
						<li role="presentation" <?php echo $lang===WT_LOCALE ? 'class="active"' : '' ?>>
							<a href="<?php echo get_query_url(array('lang'=>$lang), '&amp;'); ?>">
								<?php echo $language; ?>
							</a>
						</li>
						<?php endforeach; ?>
						<?php } else { ?>
							<li class="disabled">
								<a href="#">
									<!-- Cannot translate this - obviously! -->
									No languages installed
								</a>
							</li>
						<?php } ?>
					</ul>
				</li>
				<!-- SIGN OUT -->
				<li>
					<a href="logout.php">
						<i class="fa fa-sign-out"></i>
						<?php echo /* I18N: Menu item */ WT_I18N::translate('Logout'); ?>
					</a>
				</li>
			</ul>
		</nav>

		<?php foreach (WT_FlashMessages::getMessages() as $message): ?>
		<p class="alert alert-info alert-dismissible" role="alert">
			<?php echo $message; ?>
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		</p>
		<?php endforeach; ?>

		<div id="content">
