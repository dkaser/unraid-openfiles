<?php

namespace EDACerton\OpenFiles;

use EDACerton\PluginUtils\Translator;
use EDACerton\PluginUtils\Utils;

/*
    Copyright (C) 2025  Derek Kaser

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
}

$tr    = $tr    ?? new Translator(PLUGIN_ROOT);
$utils = $utils ?? new Utils(PLUGIN_NAME);

// Fix for black theme in Unraid 7.1 and earlier
$vars = parse_ini_file('/usr/local/emhttp/state/var.ini');
if (version_compare($vars['version'] ?? "", '7.1', '<=')) {
    echo "<style>div.dtcc-dropdown * { color: black }</style>";
}
?>
<script src="/plugins/open.files/assets/translate.js"></script>
<script>
    const translator = new Translator("/plugins/open.files");
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

<?= $utils->getLicenseBlock(); ?>

<script>
$(document).ready( async function () {
    await translator.init();
    $('#fileTable').DataTable(getDatatableConfig('/plugins/open.files/data.php/files'));
} );
</script>

