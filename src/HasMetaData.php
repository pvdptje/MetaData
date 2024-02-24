<?php 
declare(strict_types=1);
namespace DigitalRuby\MetaData;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasMetaData
 * 
 * Provides functionality to associate meta data with an Eloquent model.
 */
trait HasMetaData
{
    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta(): MorphMany
    {
        return $this->morphMany(MetaData::class, 'entity');
    }

    /**
     * Set a meta data value for the given key.
     * Updates or creates meta data entry.
     *
     * @param string $key   The meta data key.
     * @param mixed  $value The meta data value.
     * @return void
     */
    public function setMeta($key, $value): void
    {
        $this->meta()->updateOrCreate(['key' => $key], ['value' => $this->encodeIfEncodable($value)]);
    }

    /**
     * Get a meta data value for a given key.
     * Optionally allows for additional query modifications and can return either a single or multiple records.
     *
     * @param mixed    $key           The meta data key.
     * @param callable $queryCallback Optional. Additional query modifications.
     * @param bool     $singleRecord  Determines if a single or multiple records should be returned.
     * @return mixed
     */
    public function getMeta($key, $queryCallback = null, $singleRecord = true): mixed
    {
        $query = $this->meta()->when($key, fn($query) => $query->where('key', $key))
            ->when($queryCallback, fn($query) => $queryCallback($query));

        return $singleRecord ?
            $this->decodeIfJson($query->first()?->value) :
            $query->get()->pluck('value', 'key')->map(fn($value) => $this->decodeIfJson($value));
    }

    /**
     * Retrieve all meta data for the entity.
     * Optionally allows for additional query modifications.
     *
     * @param callable|null $queryCallback Optional. Additional query modifications.
     * @return mixed
     */
    public function getAllMeta($queryCallback = null): mixed
    {
        return $this->getMeta(key: false, queryCallback: $queryCallback, singleRecord: false);
    }

    /**
     * Delete a meta data entry by key.
     *
     * @param string $key The meta data key to delete.
     * @return void
     */
    public function deleteMeta($key): void
    {
        $this->meta()->where('key', $key)->delete();
    }

    /**
     * Decode a value if it is JSON, otherwise return the value as is.
     *
     * @param mixed $value       The value to decode.
     * @param bool  $associative Whether to return an associative array.
     * @return mixed
     */
    protected function decodeIfJson($value = null, ?bool $associative = true): mixed
    {
        if (is_string($value) && is_array(json_decode($value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            return json_decode($value, $associative);
        }

        return $value;
    }

    /**
     * Encode a value to JSON if it is encodable (array or object), otherwise return the value as is.
     *
     * @param mixed $value The value to encode.
     * @return mixed
     */
    protected function encodeIfEncodable($value): mixed
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
