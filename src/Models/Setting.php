<?php

namespace Yuraplohov\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sarbas\Setting\Exceptions\SettingDoesNotExist;

class Setting extends Model
{
    use HasFactory;

    /**
     * String type of value
     */
    const TYPE_STRING = 'string';

    /**
     * Integer type of value
     */
    const TYPE_INT = 'int';

    /**
     * Float type of value
     */
    const TYPE_FLOAT = 'float';

    /**
     * Boolean type of value
     */
    const TYPE_BOOL = 'bool';

    /**
     * Get value of setting
     *
     * @param string $key
     * @return string|int|float|bool|null
     * @throws \Exception
     */
    public static function get(string $key)
    {
        if (config('setting.cache')) {
            $value = cache(config('setting.cache_prefix') . $key);

            if (isset($value)) {
                return $value;
            }
        }

        $setting = self::where('key', $key)->first();

        if (!isset($setting)) {
            throw SettingDoesNotExist::withKey($key);
        }

        $setting->cast()->remember();

        return $setting->value;
    }

    /**
     * Cast value type of setting
     *
     * @return $this
     */
    public function cast(): self
    {
        $this->value = self::castValue($this->value, $this->type);

        return $this;
    }

    /**
     * Cast value type
     *
     * @param string|null $value
     * @param string $type
     * @return string|int|float|bool|null
     */
    protected static function castValue($value, string $type)
    {
        if ($type == Setting::TYPE_BOOL) {
            return $value == '1';
        }

        if ($type == Setting::TYPE_INT && $value !== null) {
            return (int) $value;
        }

        if ($type == Setting::TYPE_FLOAT && $value !== null) {
            return (float) $value;
        }

        return $value;
    }

    /**
     * Set new value of setting
     *
     * @param string $key
     * @param $value
     * @throws SettingDoesNotExist
     */
    public static function set(string $key, $value): void
    {
        $setting = Setting::where('key', $key)->first();

        if (!isset($setting)) {
            throw SettingDoesNotExist::withKey($key);
        }

        $setting->value = $value;

        $setting->save();

        $setting->remember();
    }

    /**
     * Put value of setting to cache
     *
     * @return $this
     * @throws \Exception
     */
    protected function remember(): self
    {
        if (config('setting.cache')) {
            cache()->put(config('setting.cache_prefix') . $this->key, $this->value);
        }

        return $this;
    }

    /**
     *  Clear settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();

        foreach ($settings as $setting) {
            $setting->forget();
        }
    }

    /**
     * Forget value of setting
     *
     * @throws \Exception
     */
    protected function forget(): void
    {
        cache()->forget(config('setting.cache_prefix') . $this->key);
    }
}
