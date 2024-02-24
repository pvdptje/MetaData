# DigitalRuby/MetaData

Adds a flexible key/value table to your Laravel models.

## Why this package?

I often find myself needing to store extra bits of information across entities in my apps. This could be a github link, maybe a SEO title or a path to a file. 

Instead of expanding my database with new fields across multiple entities, I often resort back to a simple `key value table` that has a `polymorphic relationship` to any entity.

This package provides you with that table and `trait` you can add to your models to implement the functionality.

## Installation

To install the package, use Composer:

```bash
composer require digitalruby/meta-data
```

After installing, you should run the migration to create the `meta_data` table in your database:

```bash
php artisan migrate
```

This command creates the necessary table for storing meta data related to your models.

## Usage

1. **Add the Trait to Your Model:**

   First, include the `HasMetaData` trait in any Eloquent model you wish to associate meta data with.

   ```php
   use DigitalRuby\MetaData\HasMetaData;

   class YourModel extends Model {
       use HasMetaData;

       // Model content
   }
   ```

2. **Setting Meta Data:**

   To add or update meta data for a model, use the `setMeta` method.

   ```php
   $model->setMeta('key', 'value');
   ```

   - `key`: The meta data key.
   - `value`: The meta data value. Can be a string, array, or object. Arrays and objects are automatically encoded to JSON.

3. **Getting Meta Data:**

   Retrieve a value with the `getMeta` method.

   ```php
   $value = $model->getMeta('key');
   ```

   Optionally, you can retrieve all meta data associated with the model:

   ```php
   $allMeta = $model->getAllMeta();
   ```

4. **Deleting Meta Data:**

   To remove a meta data entry, use `deleteMeta`.

   ```php
   $model->deleteMeta('key');
   ```

   This will delete the meta data entry with the specified key.

5. **Advanced Usage:**

   The `getMeta` method supports additional query modifications via a callback function, allowing for more complex queries.

## Requirements

- PHP 8 or higher
- Laravel 10 or higher

## Contributing

We welcome contributions! Please submit pull requests for bug fixes, features, or improvements.

## License

The DigitalRuby/MetaData package is open-sourced software licensed under the MIT license.
