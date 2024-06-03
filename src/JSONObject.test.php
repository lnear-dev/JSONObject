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
use InvalidArgumentException;
use Lame\JSONObject;

beforeEach(function (): void {
    $this->jsonObject = new class ('{"name": "John", "age": 30}', ['name' => 'John', 'age' => 30]) extends JSONObject {
        public function __construct(string $source, ?array $values = null)
        {
            $this->rawData = $source;
            $this->data = $values ?? $this->load();
        }

        public function load(): array
        {
            return json_decode($this->rawData, true) ?? [];
        }

        public function save(): void
        {
            $this->rawData = json_encode($this->data);
        }
    };
});

test('put method adds a single key-value pair', function (): void {
    $this->jsonObject->put('city', 'New York');
    expect($this->jsonObject->get('city'))->toBe('New York');
});

test('put method adds multiple key-value pairs', function (): void {
    $this->jsonObject->put(['city' => 'New York', 'country' => 'USA']);
    expect($this->jsonObject->get('city'))->toBe('New York');
    expect($this->jsonObject->get('country'))->toBe('USA');
});

test('push method appends a value to an existing array', function (): void {
    $this->jsonObject->put('hobbies', ['reading']);
    $this->jsonObject->push('hobbies', 'travelling');
    expect($this->jsonObject->get('hobbies'))->toBe(['reading', 'travelling']);
});

test('prepend method prepends a value to an existing array', function (): void {
    $this->jsonObject->put('hobbies', ['reading']);
    $this->jsonObject->prepend('hobbies', 'travelling');
    expect($this->jsonObject->get('hobbies'))->toBe(['travelling', 'reading']);
});

test('get method retrieves the value for a given key', function (): void {
    expect($this->jsonObject->get('name'))->toBe('John');
});

test('get method returns default value if key does not exist', function (): void {
    expect($this->jsonObject->get('non_existing', 'default'))->toBe('default');
});

test('has method checks if a key exists', function (): void {
    expect($this->jsonObject->has('name'))->toBeTrue();
    expect($this->jsonObject->has('non_existing'))->toBeFalse();
});

test('all method returns all data', function (): void {
    expect($this->jsonObject->all())->toBe(['name' => 'John', 'age' => 30]);
});

test('startingWith method returns keys starting with a prefix', function (): void {
    $this->jsonObject->put('country', 'USA');
    $this->jsonObject->put('continent', 'North America');
    expect($this->jsonObject->startingWith('c'))->toBe(['country' => 'USA', 'continent' => 'North America']);
});

test('forget method removes a key', function (): void {
    $this->jsonObject->forget('name');
    expect($this->jsonObject->has('name'))->toBeFalse();
});

test('flush method clears all data', function (): void {
    $this->jsonObject->flush();
    expect($this->jsonObject->all())->toBe([]);
});

test('flushStartingWith method clears keys starting with a prefix', function (): void {
    $this->jsonObject->put('city', 'New York');
    $this->jsonObject->flushStartingWith('c');
    expect($this->jsonObject->all())->toBe(['name' => 'John', 'age' => 30]);
});

test('pull method retrieves and removes a key', function (): void {
    $value = $this->jsonObject->pull('name');
    expect($value)->toBe('John');
    expect($this->jsonObject->has('name'))->toBeFalse();
});

test('increment method increases a numeric value', function (): void {
    $this->jsonObject->increment('age', 5);
    expect($this->jsonObject->get('age'))->toBe(35);
});

test('decrement method decreases a numeric value', function (): void {
    $this->jsonObject->decrement('age', 5);
    expect($this->jsonObject->get('age'))->toBe(25);
});

test('increment method throws an exception for non-numeric values', function (): void {
    $this->jsonObject->put('non_numeric', 'string');
    $this->jsonObject->increment('non_numeric');
})->throws(InvalidArgumentException::class);

test('ArrayAccess implementation works', function (): void {
    expect(isset($this->jsonObject['name']))->toBeTrue();
    expect($this->jsonObject['name'])->toBe('John');

    $this->jsonObject['name'] = 'Doe';
    expect($this->jsonObject['name'])->toBe('Doe');
    unset($this->jsonObject['name']);
    expect(isset($this->jsonObject['name']))->toBeFalse();
});

test('Countable implementation works', function (): void {
    expect(count($this->jsonObject))->toBe(2);
    $this->jsonObject->put('city', 'New York');
    expect(count($this->jsonObject))->toBe(3);
});
