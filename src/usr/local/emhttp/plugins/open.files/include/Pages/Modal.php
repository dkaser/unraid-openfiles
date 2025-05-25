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

$usage_cfg       = parse_ini_file("/boot/config/plugins/" . PLUGIN_NAME . "/usage.cfg", false, INI_SCANNER_RAW) ?: array();
$modal_displayed = $usage_cfg['modal_displayed'] ?? "no";

if ($modal_displayed === "yes") {
    // If the modal has already been displayed, we don't need to show it again.
    return;
}

?>

<script>
if ($.cookie('dwsnapwarning')===undefined) {
        swal({
            title: "<?= $tr->tr("metrics.metrics"); ?>",
            text: "<?= $tr->tr("metrics.modal"); ?>",
            type: "info",
            confirmButtonText: "Agree",
            showCancelButton: true,
            cancelButtonText: "Decline",
            html: true
            },
            function(isConfirmed){
                if (isConfirmed) {
                    // User agreed, submit the form to allow usage.
                    document.acceptMetrics.submit();
                } else {
                    // User declined, submit the form to deny usage.
                    document.denyMetrics.submit();
                }
            });
    }
</script>

<form method="POST" name="acceptMetrics" action="/update.php" target="progressFrame">
<input type="hidden" name="#file" value="/boot/config/plugins/<?= PLUGIN_NAME; ?>/usage.cfg">
<input type="hidden" name="usage_allowed" value="yes">
<input type="hidden" name="modal_displayed" value="yes">
</form>

<form method="POST" name="denyMetrics" action="/update.php" target="progressFrame">
<input type="hidden" name="#file" value="/boot/config/plugins/<?= PLUGIN_NAME; ?>/usage.cfg">
<input type="hidden" name="usage_allowed" value="no">
<input type="hidden" name="modal_displayed" value="yes">
</form>