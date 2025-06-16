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

class Process
{
    private int $PID;

    /**
     * @var string[] $files
     */
    private array $files          = [];
    private int $blocking         = 0;
    private string $name          = "";
    private string $containerName = "";

    public function __construct(int $PID)
    {
        $this->PID = $PID;

        // Look for a /proc/PID/cgroup file to determine if this is a Docker container.
        // If it is, we can extract the contained ID from it and then use that to obtain the container name.
        $cgroupFile = "/proc/{$PID}/cgroup";

        if (file_exists($cgroupFile)) {
            $cgroupContent = file_get_contents($cgroupFile);
            if ($cgroupContent !== false) {
                // Extract the container ID from the cgroup file content
                if (preg_match('/docker\/([a-z0-9]{64})/', $cgroupContent, $matches)) {
                    $containerID = escapeshellarg($matches[1]);
                    // Use the container ID to get the container name
                    $this->containerName = trim(shell_exec("docker ps --filter id={$containerID} --format '{{.Names}}'") ?: "");
                }
            }
        }
    }

    public function addFile(string $file): void
    {
        $this->files[] = $file;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addBlocking(): void
    {
        $this->blocking++;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string, string|int|bool|array<string>>
     */
    public function toArray(): array
    {
        return [
            "PID"           => $this->PID,
            "count"         => count($this->files),
            "files"         => $this->files,
            "blocks"        => $this->blocking,
            "blocking"      => $this->blocking > 0,
            "name"          => $this->name,
            "containerName" => $this->containerName,
        ];
    }
}
