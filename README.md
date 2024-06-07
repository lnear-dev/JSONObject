# JSONObject Library

This package provides a robust abstraction for handling JSON objects in PHP. It includes functionalities for loading, saving, and manipulating JSON data with a fluent API. 

## Features

- Load and save JSON data.
- Access and modify JSON data using array syntax.
- Push, prepend, and merge data into JSON objects.
- Filter, map, reduce, and sort JSON data.
- Increment and decrement numeric values.
- Convert JSON objects to and from JSON strings.
- Fluent API for chaining method calls.

## Installation

You can install the package via composer:

```bash
composer require lnear/json
```

## Usage

### Basic Usage

```php
use Lame\JSONFile;

// Instantiate JSONFile with a file path
$jsonFile = new JSONFile('/path/to/file.json');

// Access and manipulate JSON data
$jsonFile->put('key', 'value');
$jsonFile->save(); // Save changes to the file

// Load JSON data from the file
$data = $jsonFile->load();
```

### JSONString

The `JSONString` class handles JSON data stored as a string. It also extends the `JSONObject` abstract class and provides methods to work with JSON data in string format.

### Usage

```php
use Lame\JSONString;

// Instantiate JSONString with a JSON string
$jsonString = new JSONString('{"key": "value"}');

// Access and manipulate JSON data
$jsonString->put('newKey', 'newValue');
$jsonString->save(); // Save changes to the JSON string

// Load JSON data from the string
$data = $jsonString->load();
```

## Exception Handling

Both classes handle exceptions related to JSON parsing and file operations. They throw appropriate exceptions when errors occur, allowing you to handle them gracefully in your application.

### ArrayAccess and Countable

```php
if ($jsonObject->has('name')) {
    echo $jsonObject['name']; // Outputs: John
}

$jsonObject['email'] = 'john.doe@example.com';
unset($jsonObject['age']);
echo count($jsonObject); // Outputs the count of items in the JSON object
```

### Data Manipulation

```php
$jsonObject->push('tags', 'developer');
$jsonObject->prepend('tags', 'programmer');
$jsonObject->increment('age', 2);
$jsonObject->decrement('age', 1);

$allData = $jsonObject->all();
$filteredData = $jsonObject->filter(fn($key, $value) => is_string($value));
```

### Advanced Methods

```php
$keysStartingWithA = $jsonObject->startingWith('a');
$jsonObject->forget('location');
$jsonObject->flush();
$jsonObject->flushStartingWith('temp_');

$jsonObject->each(fn($key, $value) => print("Key: $key, Value: $value"));
$mappedData = $jsonObject->map(fn($value) => strtoupper($value));
$reducedData = $jsonObject->reduce(fn($carry, $value) => $carry . $value, '');
$sortedData = $jsonObject->sort(fn($a, $b) => $a <=> $b);
$reversedData = $jsonObject->reverse();
```

## License

This project is licensed under the MIT License - see the [LICENSE](https://github.com/lnear-dev/JSONObject/blob/main/LICENSE) file for details.

## Documentation

For detailed documentation, please visit [JSONObject Documentation](https://docs.lnear.dev/json).

## Contributing

We welcome contributions! Please read our [Contributing Guidelines](https://github.com/lnear-dev/JSONObject/blob/main/CONTRIBUTING.md) before making any contributions.

## Contact

If you have any questions or feedback, feel free to reach out at [hi@lnear.dev](mailto:hi@lnear.dev).

## Security

If you discover any security related issues, please email hi@lnear.dev instead of using the issue tracker.

## Credits

- [Lanre Waju](https://github.com/oplanre)
- [All Contributors](../../contributors)
