#!/usr/bin/php -q
<?php

namespace OpenFiles;

$fileRoot = dirname(__FILE__);

require_once "{$fileRoot}/include/common.php";

Utils::run_task(__NAMESPACE__ . '\Utils::sendUsageData');
