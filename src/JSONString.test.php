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
use JsonException;
use Lame\JSONString;
use RuntimeException;

beforeEach(function (): void {
    $this->jsonString = new JSONString('{"name": "John", "age": 30}');
});

describe('JSONString', function (): void {
    test('constructor initializes with valid JSON string', function (): void {
        expect($this->jsonString->get('name'))->toBe('John');
        expect($this->jsonString->get('age'))->toBe(30);
    });

    test('constructor initializes with array values', function (): void {
        $jsonString = new JSONString('{"name": "John"}', ['name' => 'Doe', 'age' => 25]);
        expect($jsonString->get('name'))->toBe('Doe');
        expect($jsonString->get('age'))->toBe(25);
    });

    test('make method initializes with valid JSON string', function (): void {
        $jsonString = JSONString::make('{"city": "New York"}');
        expect($jsonString->get('city'))->toBe('New York');
    });

    test('make method initializes with valid array', function (): void {
        $jsonString = JSONString::make(['country' => 'USA']);
        expect($jsonString->get('country'))->toBe('USA');
    });

    test('make method throws exception for invalid data type', function (): void {
        JSONString::make(12345);
    })->throws(InvalidArgumentException::class);

    test('load method decodes valid JSON string', function (): void {
        expect($this->jsonString->all())->toBe(['name' => 'John', 'age' => 30]);
    });

    test('load method throws exception for invalid JSON string', function (): void {
        new JSONString('{invalid json}');
    })->throws(RuntimeException::class);

    test('save method encodes data to JSON string', function (): void {
        $this->jsonString->put('city', 'New York');
        $this->jsonString->save();
        expect($this->jsonString->raw())->toBe('{"name":"John","age":30,"city":"New York"}');
    });

    test('save method throws exception for JSON encoding error', function (): void {
        $this->jsonString->put('invalid', "\xB1\x31"); // Invalid UTF-8 sequence
        unset($this->jsonString); // Force save
    })->throws(JsonException::class, 'Failed to save data to JSON string: Malformed UTF-8 characters, possibly incorrectly encoded');

    test('put method adds a key-value pair', function (): void {
        $this->jsonString->put('city', 'New York');
        expect($this->jsonString->get('city'))->toBe('New York');
    });

    test('get method retrieves a value for a given key', function (): void {
        expect($this->jsonString->get('name'))->toBe('John');
    });

    test('get method returns default value if key does not exist', function (): void {
        expect($this->jsonString->get('non_existing', 'default'))->toBe('default');
    });

    test('has method checks if a key exists', function (): void {
        expect($this->jsonString->has('name'))->toBeTrue();
        expect($this->jsonString->has('non_existing'))->toBeFalse();
    });

    test('all method returns all data', function (): void {
        expect($this->jsonString->all())->toBe(['name' => 'John', 'age' => 30]);
    });

    test('forget method removes a key', function (): void {
        $this->jsonString->forget('name');
        expect($this->jsonString->has('name'))->toBeFalse();
    });

    test('flush method clears all data', function (): void {
        $this->jsonString->flush();
        expect($this->jsonString->all())->toBe([]);
    });

    test('pull method retrieves and removes a key', function (): void {
        $value = $this->jsonString->pull('name');
        expect($value)->toBe('John');
        expect($this->jsonString->has('name'))->toBeFalse();
    });

    test('increment method increases a numeric value', function (): void {
        $this->jsonString->increment('age', 5);
        expect($this->jsonString->get('age'))->toBe(35);
    });

    test('decrement method decreases a numeric value', function (): void {
        $this->jsonString->decrement('age', 5);
        expect($this->jsonString->get('age'))->toBe(25);
    });

    test('increment method throws exception for non-numeric values', function (): void {
        $this->jsonString->put('non_numeric', 'string');
        $this->jsonString->increment('non_numeric');
    })->throws(InvalidArgumentException::class);

    test('ArrayAccess implementation works', function (): void {
        expect(isset($this->jsonString['name']))->toBeTrue();
        expect($this->jsonString['name'])->toBe('John');

        $this->jsonString['name'] = 'Doe';
        expect($this->jsonString['name'])->toBe('Doe');

        unset($this->jsonString['name']);
        expect(isset($this->jsonString['name']))->toBeFalse();
    });

    test('Countable implementation works', function (): void {
        expect(count($this->jsonString))->toBe(2);
        $this->jsonString->put('city', 'New York');
        expect(count($this->jsonString))->toBe(3);
    });
});
