<?php

declare(strict_types=1);
/**
 * This file is part of a Lnear project.
 *
 * (c) 2024 Lanre Ajao(lnear)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * .........<-..(`-')_..(`-').._(`-').._....(`-').
 * ...<-.......\(.OO).).(.OO).-/(OO.).-/.<-.(OO.).
 * .,--..)..,--./.,--/.(,------./.,---...,------,)
 * .|..(`-')|...\.|..|..|...---'|.\./`.\.|.../`..'
 * .|..|OO.)|....'|..|)(|..'--..'-'|_.'.||..|_.'.|
 * (|..'__.||..|\....|..|...--'(|...-...||.......'
 * .|.....|'|..|.\...|..|..`---.|..|.|..||..|\..\.
 * .`-----'.`--'..`--'..`------'`--'.`--'`--'.'--'
 * @link     https://github.com/lnear-dev/JSONObject
 * @licence  https://github.com/lnear-dev/JSONObject/blob/main/LICENSE
 * @document https://docs.lnear.dev/json
 * @contact  hi@lnear.dev
 */

namespace Lame;

use InvalidArgumentException;
use JsonException;
use RuntimeException;

class JSONFile extends JSONObject
{
    protected string $fileName;

    public function __construct(string $source, ?array $values = null)
    {
        $this->setFileName($source);
        $this->data = $values ?? $this->load();
    }

    public function load(): array
    {
        if (! file_exists($this->fileName)) {
            return [];
        }

        try {
            return json_decode(file_get_contents($this->fileName), true, 512, JSON_THROW_ON_ERROR);
        } catch (InvalidArgumentException|JsonException $e) {
            throw new RuntimeException("Failed to load data from {$this->fileName}: " . $e->getMessage());
        }
    }

    public function save(): void
    {
        if (empty($this->data)) {
            @unlink($this->fileName);
        } else {
            try {
                file_put_contents($this->fileName, json_encode($this->data, JSON_THROW_ON_ERROR));
            } catch (JsonException $e) {
                throw new RuntimeException("Failed to save data to {$this->fileName}: " . $e->getMessage());
            }
        }
    }

    protected function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;
        return $this;
    }
}
