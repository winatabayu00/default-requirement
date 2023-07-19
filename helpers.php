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

if (! function_exists('fluent')) {
    /**
     * @param mixed|null $data
     *
     * @return \Illuminate\Support\Fluent
     */
    function fluent(mixed $data = null): \Illuminate\Support\Fluent
    {
        if (! (is_array($data) || is_object($data))) {
            $data = [];
        }

        return new \Illuminate\Support\Fluent($data);
    }
}

if (! function_exists('carbon')) {
    /**
     * @param string|\DateTimeInterface|null $datetime
     * @param \DateTimeZone|string|null      $timezone
     * @param string                         $locale
     *
     * @return \Illuminate\Support\Carbon
     */
    function carbon(
        string|DateTimeInterface|null $datetime = null,
        string|DateTimeZone|null $timezone = null,
        string $locale = 'id_ID'
    ): \Illuminate\Support\Carbon {
        if (auth()->check()) {
            if (! $timezone && (auth()->user()?->timezone ?? null)) {
                $timezone = auth()->user()->timezone;
            }
            if (! $locale && (auth()->user()?->locale ?? null)) {
                $locale = auth()->user()->locale;
            }
        }

        try {
            \Illuminate\Support\Carbon::setLocale($locale);
        } catch (\Exception $e) {
            //
        }

        if (! $datetime) {
            return \Illuminate\Support\Carbon::now()->timezone($timezone);
        }

        return \Illuminate\Support\Carbon::parse($datetime)->timezone($timezone);
    }
}

if (! function_exists('routed')) {
    /**
     * Existing Route by Name
     * with '#' fallback.
     *
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     */
    function routed(string $name, array $parameters = [], bool $absolute = true): string
    {
        if (app('router')->has($name)) {
            return app('url')->route($name, $parameters, $absolute);
        }

        return '#';
    }
}

if (! function_exists('activeRoute')) {
    /**
     * @param string $route
     * @param array  $params
     *
     * @return bool
     */
    function activeRoute(string $route = '', array $params = []): bool
    {
        if (empty($route = trim($route))) {
            return false;
        }

        try {
            if (request()->routeIs($route, "{$route}.*")) {
                if (empty($params)) {
                    return true;
                }

                $requestRoute = request()->route();
                $paramNames = $requestRoute->parameterNames();

                foreach ($params as $key => $value) {
                    if (is_int($key)) {
                        $key = $paramNames[$key];
                    }

                    if (
                        $requestRoute->parameter($key) instanceof \Illuminate\Database\Eloquent\Model
                        && $value instanceof \Illuminate\Database\Eloquent\Model
                        && $requestRoute->parameter($key)->id != $value->id
                    ) {
                        return false;
                    }

                    if (is_object($requestRoute->parameter($key))) {
                        // try to check param is enum type
                        try {
                            if ($requestRoute->parameter($key)->value && $requestRoute->parameter($key)->value != $value) {
                                return false;
                            }
                        } catch (Exception $e) {
                            return false;
                        }
                    } else {
                        if ($requestRoute->parameter($key) != $value) {
                            return false;
                        }
                    }
                }

                return true;
            }
        } catch (Exception $e) {
        }

        return false;
    }
}