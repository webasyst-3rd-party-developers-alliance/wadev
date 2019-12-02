[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)

# CakePHP Utility Classes

This is the fork of library provides a range of utility classes of CakePHP framework but without framework core
dependencies.


## Why?

I like CakePHP, a powerful, beautiful and flexible PHP framework. But I can't use it with every project I work :( .
So I clone this very handy toolbox, drops out any dependencies to Cake/Core to use this library separately.   

## What's in the toolbox?

### Hash

A ``Hash`` (as in PHP arrays) class, capable of extracting data using an intuitive DSL:

```php
$things = [
    ['name' => 'Mark', 'age' => 15],
    ['name' => 'Susan', 'age' => 30],
    ['name' => 'Lucy', 'age' => 25]
];

$bigPeople = Hash::extract($things, '{n}[age>21].name');

// $bigPeople will contain ['Susan', 'Lucy']
```

Check the [official Hash class documentation](https://book.cakephp.org/3.0/en/core-libraries/hash.html)

### Inflector

The Inflector class takes a string and can manipulate it to handle word variations
such as pluralizations or camelizing.

```php
echo Inflector::pluralize('Apple'); // echoes Apples

echo Inflector::singularize('People'); // echoes Person
```

Check the [official Inflector class documentation](https://book.cakephp.org/3.0/en/core-libraries/inflector.html)

### Text

The Text class includes convenience methods for creating and manipulating strings.

```php
Text::insert(
    'My name is :name and I am :age years old.',
    ['name' => 'Bob', 'age' => '65']
);
// Returns: "My name is Bob and I am 65 years old."

$text = 'This is the song that never ends.';
$result = Text::wrap($text, 22);

// Returns
This is the song
that never ends.
```

Check the [official Text class documentation](https://book.cakephp.org/3.0/en/core-libraries/text.html)

### Xml

The Xml class allows you to easily transform arrays into SimpleXMLElement or DOMDocument objects
and back into arrays again

```php
$data = [
    'post' => [
        'id' => 1,
        'title' => 'Best post',
        'body' => ' ... '
    ]
];
$xml = Xml::build($data);
```

Check the [official Xml class documentation](https://book.cakephp.org/3.0/en/core-libraries/xml.html)
