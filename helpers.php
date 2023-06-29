<?php
if (!function_exists('isProduction')) {
    /**
     * @return bool
     */
    function isProduction(): bool
    {
        return in_array(app()->environment(), ['prod', 'production']);
    }
}

if (!function_exists('isNonProduction')) {
    /**
     * @return bool
     */
    function isNonProduction(): bool
    {
        return !isProduction();
    }
}

if (!function_exists('debugNonProduction')) {
    /**
     * @return bool
     */
    function debugNonProduction(): bool
    {
        return config('app.debug') && isNonProduction();
    }
}

if (!function_exists('setDefaultRequest')) {
    /**
     * Set Default Value for Request Input.
     *
     * @param string|array $name
     * @param null $value
     * @param bool $force
     */
    function setDefaultRequest(string|array $name, mixed $value = null, bool $force = true): void
    {
        try {
            $request = app('request');

            if (is_array($name)) {
                $data = $name;
            } else {
                $data = [$name => $value];
            }

            if ($force) {
                $request->merge($data);
            } else {
                $request->mergeIfMissing($data);
            }
            $request->session()->flashInput($data);
        } catch (Exception) {
        }
    }
}


if (!function_exists('activeUser')) {

    /**
     * Convert Array into Object in deep
     *
     * @return User|\Illuminate\Contracts\Auth\Authenticatable
     */
    function activeUser(): null|User|\Illuminate\Contracts\Auth\Authenticatable
    {
        if (auth()->check()){
            return auth()->user();
        }
        return null;
    }
}

if (!function_exists('getFillableAttribute')) {

    /**
     * Convert Array into Object in deep
     *
     * @param string $model
     * @param array $data
     * @return array
     */
    function getFillableAttribute(string $model, array $data): array
    {
        $fillable = (new $model)->getFillable();

        return Arr::only($data, Arr::flatten($fillable));
    }
}