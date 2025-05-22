<?php

namespace OpenFiles;

/* Copyright 2015-2025, Dan Landon.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */

$tr = $tr ?? new Translator();

if ( ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("PLUGIN_NAME not defined");
}

?>

<link type="text/css" rel="stylesheet" href="<?php Utils::auto_v("/plugins/open.files/assets/style-" . ($theme ?? "") . ".css");?>">
<link type="text/css" rel="stylesheet" href="<?php Utils::auto_v("/plugins/open.files/assets/open-files.css");?>">

<table class='tablesorter shift open-files' id='tblOpenFiles'>
<thead><tr><th><?= $tr->tr("process"); ?></th><th><?= $tr->tr("pid"); ?></th><th><?= $tr->tr("kill_process"); ?></th><th><?= $tr->tr("open"); ?></th><th><?= $tr->tr("prevent_shutdown"); ?></th><th><?= $tr->tr("path"); ?></th></tr></thead>
<tbody id="open-files">
	<tr>
		<td colspan='6'><div class='spinner'></div></td>
	</tr>
</tbody>
</table>

<p><strong><?= $tr->tr("notes"); ?>:</strong></p>
<li><?= $tr->tr("bash"); ?></li>
<li><?= $tr->tr("smbd"); ?></li>

<input type="button" value="<?= $tr->tr("refresh"); ?>" onclick="refresh()">
<input type="button" value="<?= $tr->tr("done"); ?>" onclick="done()">

<script>
/* URL for Open Files PHP file. */
const OFURL = '/plugins/open.files/include/OpenFiles.php';

function refreshPage() {
	$.post(OFURL, {
		'action': 'open_files',
	}, function (data) {
		if (data) {
			/* Fill the open files table. */
			$('#open-files').html(data);

			/* Set up the table sorter. */
			$('#tblOpenFiles').tablesorter({headers:{2:{sorter:false},3:{sorter:false}}});
		}
	}, 'json');
}

$(function() {
	<?php if ($resize ?? false) { ?>
	function resize() {
	  $('pre.up').height(Math.max(window.innerHeight-330,370)).show();
	}

	resize();
	$(window).bind('resize',function(){resize();});
	<?php }?>

});

refreshPage();
</script>

<?php
$usage_cfg     = parse_ini_file("/boot/config/plugins/" . PLUGIN_NAME . "/usage.cfg", false, INI_SCANNER_RAW) ?: array();
$usage_allowed = $usage_cfg['usage_allowed'] ?? "yes";
?>

<h3><?= $tr->tr("metrics.metrics"); ?></h3>

<form method="POST" action="/update.php" target="progressFrame">
<input type="hidden" name="#file" value="/boot/config/plugins/<?= PLUGIN_NAME; ?>/usage.cfg">

<dl>
        <dt><?= $tr->tr("metrics.usage"); ?></dt>
        <dd>
			<select name="usage_allowed" size="1">
				<?= Utils::make_option($usage_allowed, "yes", $tr->tr("yes"));?>
				<?= Utils::make_option($usage_allowed, "no", $tr->tr("no"));?>
			</select>
			<input type="submit" value='<?= $tr->tr("apply"); ?>'>
        </dd>
    </dl>
    <blockquote class='inline_help'><?= $tr->tr("metrics.desc"); ?></blockquote>
</form>
</div>