# litlife/id-dirname

This package is for create a folder path by ID

## Installation

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
composer require litlife/id-dirname
```

## Usage

### Generate a payment URL

In this example, you can see how to generate a link that the user can click to make a payment

```bash
use Litlife\IdDirname\IdDirname;

$id = 12345678;

$class = new IdDirname($id);
print_r($class->splitToArray());

```

Output:

`[12, 345, 678]`

## Testing
```bash
composer test
```
## License
[MIT](https://choosealicense.com/licenses/mit/)
