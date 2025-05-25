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

$usage_cfg     = parse_ini_file("/boot/config/plugins/" . PLUGIN_NAME . "/usage.cfg", false, INI_SCANNER_RAW) ?: array();
$usage_allowed = $usage_cfg['usage_allowed'] ?? "yes";

?>
<link type="text/css" rel="stylesheet" href="/plugins/open.files/assets/open-files.css">
<script src="/webGui/javascript/jquery.tablesorter.widgets.js"></script>

<p><strong><?= $tr->tr("notes"); ?>:</strong></p>
<ul>
	<li><?= $tr->tr("bash"); ?></li>
	<li><?= $tr->tr("smbd"); ?></li>
</ul>

<input type="button" value="<?= $tr->tr("refresh"); ?>" onclick="refresh()">
<input type="button" value="<?= $tr->tr("done"); ?>" onclick="done()">
<style>input.disabled { display: none; }</style>
<table class='tablesorter open-files' id='tblOpenFiles'>
<thead>
	<tr>
		<th><?= $tr->tr("process"); ?></th>
		<th><?= $tr->tr("pid"); ?></th>
		<th class="filter-false"><?= $tr->tr("kill_process"); ?></th>
		<th class="filter-false"><?= $tr->tr("open"); ?></th>
		<th class="filter-select"><?= $tr->tr("prevent_shutdown"); ?></th>
		<th><?= $tr->tr("path"); ?></th>
	</tr>
</thead>
<tbody id="open-files">
	<tr>
		<td colspan='6'><div class='spinner'></div></td>
	</tr>
</tbody>
</table>

<script>
/* URL for Open Files PHP file. */
const OFURL = '/plugins/open.files/include/OpenFiles.php';

function refreshPage() {
	$.post(OFURL, {'action': 'open_files',}, function (data) {
		if (data) {
			/* Fill the open files table. */
			$('#open-files').html(data);

			/* Set up the table sorter. */
			$('#tblOpenFiles').tablesorter({
				widthFixed : true,
				sortList: [[1,0]],
				widgets: ['stickyHeaders','filter','zebra'],
				widgetOptions: {
					// on black and white, offset is height of #menu
					// on azure and gray, offset is height of #header
					stickyHeaders_offset: ($('#menu').height() < 50) ? $('#menu').height() : $('#header').height(),
					filter_columnFilters: true,
					filter_reset: '.reset',
					filter_liveSearch: true,
					zebra: ["normal-row","alt-row"]
				},
			});
		}
	}, 'json');
}

refreshPage();
</script>
