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

use ArrayAccess;
use Countable;
use InvalidArgumentException;

abstract class JSONObject implements ArrayAccess, Countable
{
    protected string $rawData;

    protected array $data;

    abstract public function __construct(string $source, ?array $values = null);

    public function __destruct()
    {
        $this->save();
    }

    abstract public function load(): array;

    abstract public function save(): void;

    public function raw()
    {
        return $this->rawData;
    }

    public function put(array|string $name, mixed $value = null): static
    {
        if ($name !== []) {
            $this->data = array_merge($this->data, (is_array($name) ? $name : [$name => $value]));
        }
        return $this;
    }

    public function push(string $name, mixed $pushValue): static
    {
        $pushValue = (array) $pushValue;
        $oldValue = $this->get($name, []);
        if (! is_array($oldValue)) {
            $oldValue = [$oldValue];
        }
        return $this->put($name, array_merge($oldValue, $pushValue));
    }

    public function prepend(string $name, mixed $prependValue): static
    {
        $prependValue = (array) $prependValue;
        $oldValue = $this->get($name, []);

        if (! is_array($oldValue)) {
            $oldValue = [$oldValue];
        }

        return $this->put($name, array_merge($prependValue, $oldValue));
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->data[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function startingWith(string $prefix = ''): array
    {
        return self::keysStartingWith($this->data, $prefix);
    }

    public function forget(string $key): static
    {
        unset($this->data[$key]);

        return $this;
    }

    public function flush(): static
    {
        $this->data = [];

        return $this;
    }

    public function flushStartingWith(string $startingWith = ''): static
    {
        if ($startingWith === '') {
            return $this->flush();
        }

        $this->data = self::keysNotStartingWith($this->data, $startingWith);

        return $this;
    }

    public function pull(string $name): mixed
    {
        $value = $this->get($name);
        $this->forget($name);

        return $value;
    }

    public function increment(string $name, int $by = 1): mixed
    {
        $currentValue = $this->get($name, 0);

        if (! $this->isNumber($currentValue)) {
            throw new InvalidArgumentException("The value for '{$name}' is not a number.");
        }

        $newValue = $currentValue + $by;
        $this->put($name, $newValue);

        return $newValue;
    }

    public function decrement(string $name, int $by = 1): mixed
    {
        return $this->increment($name, -$by);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->put($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->forget($offset);
    }

    public function count(): int
    {
        return count($this->data);
    }

    protected static function keysStartingWith(array $values, string $startsWith): array
    {
        return array_filter($values, fn ($key) => str_starts_with($key, $startsWith), ARRAY_FILTER_USE_KEY);
    }

    protected static function keysNotStartingWith(array $values, string $startsWith): array
    {
        return array_filter($values, fn ($key) => ! str_starts_with($key, $startsWith), ARRAY_FILTER_USE_KEY);
    }

    protected function isNumber($value): bool
    {
        return is_int($value) || is_float($value);
    }
}
