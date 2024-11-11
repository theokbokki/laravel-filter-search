# Laravel filter search

> [!WARNING]
> This package wasn't yet tested in production use at your own risks. Tests should arrive soon. 

## Why?

I was making a website that needed a lot of filtering flexibility and a simple search wouldn't do.
I could have made a bunch of filters with selects, checkboxes etc, but that would quickliy have become messy.

Then I remembered how github projects does it and it solved all the problems at once! 

## Requirements

- PHP 8.0+
- Laravel 9.0+

## Installing

You can install the package via composer
```shell
composer require theokbokki/laravel-filter-search
```

## Using

The model you want to make searchable should have the trait `HasFilterSearch` and it should implement these 2 methods:
```php
public static function defaultSearchFields(): array
{
    return [
        // the list of the model fields you want to be able to seach normally.
    ];
}

public static function allowedSearchFields(): array
{
    return [
        // the list of the model fields you want to allow filtering on. 
    ];
}
```

Then when you want to search (for example in a controller), you use the `handleFilterSearch` method
```php
$query = Snippet::handleFilterSearch($searchTerm);
```

## The syntax 

The syntax was heavily inspired by the one from Github projects, here's a rundown of the possibilities.
One thing to note is that case isn't taken into account, so "TEST" and "test" are the same.

### Regular search
You can simply put a search term like normal and get what you want

| Search terms   | Result   |
|---|---|
| test   | Will return the records containg the string "test" in the fields specified in `defaultSearchFields()` |

## Field search
You can use all the fields speicifed in `allowedSearchFields()` to make the search more precise

| Search terms   | Result   |
|---|---|
| title:Test   | Will return the records containg the string "test" in the title field |

## Combining filters
You can freely mix and match search terms and filters to get very precise results

| Search terms   | Result   |
|---|---|
| title:Foo published:true Bar | Will return the records containg the string "Foo" in the title field, with published = true and Bar in the fields specified in `defaultSearchFields()` |

## Reversing the search
You can also add an hyphen before the field or keyword to reverse the search

| Search terms   | Result   |
|---|---|
| -title:Test   | Will return any record not containg the string "test" in the title field |
| -test   | Will return any record not containg the string "test" in the fields specified in `defaultSearchFields()` |

## What if my search contains space, commas or colons? 
You can add double quotes around your search to handle space, commas and colons.

| Search terms   | Result   |
|---|---|
| title:"Sentence: with spaces, colons and commas" | Will return any record containg the string "Sentence: with spaces, colons and commas" in the title field |
