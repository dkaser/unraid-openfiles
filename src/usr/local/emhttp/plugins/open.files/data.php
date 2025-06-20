<?php

namespace EDACerton\OpenFiles;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

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

require_once dirname(__FILE__) . "/include/common.php";

$prefix = "/plugins/open.files/data.php";

if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
}

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

$app->get("{$prefix}/files", function (Request $request, Response $response, $args) {
    $lsof    = new LSOF();
    $payload = json_encode($lsof->getProcesses()) ?: "[]";
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get("{$prefix}/locales", function (Request $request, Response $response, $args) {
    // Get the list of supported locales from /locales (each locale is a JSON file)
    $localesDir = PLUGIN_ROOT . '/locales';
    $files      = scandir($localesDir);

    foreach ($files as $key => $file) {
        if ( ! is_file($localesDir . '/' . $file) || pathinfo($file, PATHINFO_EXTENSION) !== 'json') {
            unset($files[$key]);
        } else {
            $files[$key] = basename($file, '.json'); // Store only the locale name without extension
        }
    }

    $response->getBody()->write(json_encode($files) ?: "[]");
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withHeader('Cache-Control', 'no-store')
        ->withStatus(200);
});

$app->run();
