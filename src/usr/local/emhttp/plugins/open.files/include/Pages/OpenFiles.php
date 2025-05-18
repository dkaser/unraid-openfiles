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