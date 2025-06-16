<?php

namespace EDACerton\OpenFiles;

/*
    Copyright (C) 2025  Derek Kaser
    Copyright 2015-2025, Dan Landon.

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

class LSOF
{
    /**
     * @var list<array<string, array<string>|bool|int|string>>
     */
    private array $processes = array();

    public function __construct()
    {
        $this->processes = $this->getLSOF();
    }

    /**
     * @return list<array<string, array<string>|bool|int|string>>
     */
    public function getProcesses(): array
    {
        return $this->processes;
    }

    /**
     * @return list<array<string, array<string>|bool|int|string>>
     */
    private function getLSOF(): array
    {
        $retval = array();

        // cd to /tmp or else lsof itself will show up as working dir on websserver home.
        $timeout = 30;
        $startTime = microtime(true);

        // Build the lsof command with safe argument escaping
        $lsofCmd = sprintf(
            '/usr/bin/timeout %s bash -c %s',
            escapeshellarg((string)$timeout),
            escapeshellarg('cd /tmp; /usr/bin/lsof -F facn /mnt/* /dev/loop* /dev/md* 2>/dev/null')
        );

        $res = shell_exec($lsofCmd);

        $time = microtime(true) - $startTime;

        $cwd = false;

        if ($time < $timeout) {
            $res1 = isset($res) ? explode("\n", $res ?: "") : array();

            $currentProcess = null;

            foreach ($res1 as $stg) {
                $c   = substr($stg, 0, 1);
                $var = substr($stg, 1);

                switch ($c) {
                    case "c":
                        if ($currentProcess) {
                            $currentProcess->setName($var);
                        }
                        break;
                    case "n":
                        if ($cwd) {
                            $var .= " (working directory)";
                            $cwd = false;
                        }
                        if ($currentProcess) {
                            $currentProcess->addFile($var);
                        }
                        break;
                    case "a":
                        if ($currentProcess && ($var == "u" || $var == "w")) {
                            $currentProcess->addBlocking();
                        }
                        break;
                    case "f":
                        if ($currentProcess && $var == "cwd" && $currentProcess->getName() != 'smbd') {
                            $currentProcess->addBlocking();
                            $cwd = true;
                        }
                        break;
                    case "p":
                        if ($currentProcess !== null) {
                            $retval[] = $currentProcess->toArray();
                        }
                        $currentProcess = new Process(intval($var));
                        break;
                    default:
                        break;
                }
            }
        }

        // Add the last process from the loop
        if ($currentProcess !== null && $currentProcess->hasFiles()) {
            $retval[] = $currentProcess->toArray();
        }

        return $retval;
    }
}
