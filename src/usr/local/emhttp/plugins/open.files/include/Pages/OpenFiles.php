<?php

namespace EDACerton\OpenFiles;

use EDACerton\PluginUtils\Translator;

/* Copyright 2015-2025, Dan Landon.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */

if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
}

$tr = $tr ?? new Translator(PLUGIN_ROOT);

?>
<script src="/plugins/open.files/assets/translate.js"></script>
<script>
    const translator = new Translator("/plugins/open.files/");
</script>

<link type="text/css" rel="stylesheet" href="/plugins/open.files/assets/style.css">

<link href="/plugins/open.files/assets/datatables.min.css" rel="stylesheet">
<script src="/plugins/open.files/assets/datatables.min.js"></script>

<script src="/plugins/open.files/assets/openfiles.js"></script>

<table id='fileTable' class="stripe compact">
    <thead>
        <tr>
            <th><strong><?= $tr->tr("pid"); ?></strong></th>
            <th><strong><?= $tr->tr("process"); ?></strong></th>
            <th><strong><?= $tr->tr("container"); ?></strong></th>
			<th><strong><?= $tr->tr("kill_process"); ?></strong></th>
            <th><strong><?= $tr->tr("open"); ?></strong></th>
            <th><strong><?= $tr->tr("prevent_shutdown"); ?></strong></th>
			<th><strong><?= $tr->tr("path"); ?></strong></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
    </tfoot>
</table>

<script>
$(document).ready( async function () {
    await translator.init();
    $('#fileTable').DataTable(getDatatableConfig('/plugins/open.files/data.php/files'));
} );
</script>

