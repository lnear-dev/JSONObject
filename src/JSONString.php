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

class JSONString extends JSONObject
{
    public function __construct(string $source, ?array $values = null)
    {
        $this->rawData = $source;
        $this->data    = $values ?? $this->load();
    }

    public static function make(mixed $data): self
    {
        if (is_string($data) && json_validate($data)) {
            return new self($data);
        }
        if (is_array($data) || is_object($data)) {
            return new self(json_encode($data));
        }
        throw new InvalidArgumentException('Invalid data type provided.');
    }

    public function load(): array
    {
        try {
            return json_decode($this->rawData, true, 512, JSON_THROW_ON_ERROR);
        } catch (InvalidArgumentException | JsonException $e) {
            throw new RuntimeException('Failed to load data from JSON string: ' . $e->getMessage());
        }
    }

    public function save(): void
    {
        // $this->rawData = json_encode($this->data, JSON_THROW_ON_ERROR);
        try {
            $this->rawData = json_encode($this->data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonException('Failed to save data to JSON string: ' . $e->getMessage());
        }
    }
}
