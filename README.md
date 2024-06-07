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
use Lame\JSONObject;

class MyJSONObject extends JSONObject
{
    public function __construct(string $source, ?array $values = null)
    {
        $this->rawData = $source;
        $this->data = $values ?? json_decode($source, true);
    }

    public function load(): array
    {
        return $this->data;
    }

    public function save(): void
    {
        $this->rawData = json_encode($this->data);
    }
}

$jsonString = '{"name": "John", "age": 30}';
$jsonObject = new MyJSONObject($jsonString);

echo $jsonObject->get('name'); // Outputs: John
$jsonObject->put('location', 'New York');
echo $jsonObject->toJson(); // Outputs: {"name":"John","age":30,"location":"New York"}
```

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

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## Credits

- [Lanre Waju](https://github.com/oplanre)
- [All Contributors](../../contributors)
