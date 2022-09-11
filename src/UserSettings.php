<?php

namespace EightAndDouble\UserSettings;

use Illuminate\Support\Arr;

class UserSettings
{
    /**
     * Construction method to read package configuration.
     *
     * @return void
     */
    public function __construct(
        /** Users Table Name @var string */
        protected string $table = '',
        /** Settings Column Name @var string */
        protected string $column = '',
        /** Custom Constraint Value - ID of the required user @var string */
        protected string $custom_constraint_value = '',
        /** Auth User's ID @var string */
        protected string $default_constraint_key = '',
        /** Default Constrain Column - Got from config @var string */
        protected string $default_constraint_value = '',
        /** Settings Array for each user @var array */
        protected array $settings = [],
        /** Dirty Status of each settings array @var array */
        protected array $dirty = [],
        /** Loaded status of each settings array @var array */
        protected array $loaded = [],
    ) {
        $this->table = (new (config('usersettings.users')))->getTable();
        $this->column = config('usersettings.settings_column');
        $this->default_constraint_key = config('usersettings.default_constraint_key');
        $this->default_constraint_value = (\Auth::check() ? \Auth::id() : '');
    }

    /**
     * Get the value of a specific setting.
     */
    public function get(string $dot_notation, ?string $custom_constraint_value = null, null|string|array $default = null): string|null|array
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);

        return data_get($this->settings[$set_constraint_value], $dot_notation, $default);
    }

    /**
     * Set the value of a specific setting.
     */
    public function set(string|array $dot_notation, ?string $value = null, ?string $custom_constraint_value = null): void
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);
        $this->dirty[$set_constraint_value] = true;

        if (is_array($dot_notation)) {
            foreach ($dot_notation as $key => $value) {
                data_set($this->settings[$set_constraint_value], $key, $value);
            }
        } else {
            data_set($this->settings[$set_constraint_value], $dot_notation, $value);
        }

        $this->save($set_constraint_value);
    }

    /**
     * Unset a specific setting.
     */
    public function forget(string $dot_notation, ?string $custom_constraint_value = null): void
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);

        if (Arr::has($this->settings[$set_constraint_value], $dot_notation)) {
            Arr::forget($this->settings[$set_constraint_value], $dot_notation);
            $this->dirty[$set_constraint_value] = true;
        }

        $this->save($set_constraint_value);
    }

    /**
     * Check for the existence of a specific setting.
     */
    public function has(string $dot_notation, ?string $custom_constraint_value = null): bool
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);

        if (! Arr::has($this->settings, $set_constraint_value)) {
            return false;
        }

        return Arr::has($this->settings[$set_constraint_value], $dot_notation);
    }

    /**
     * Return the entire settings array.
     */
    public function all(?string $custom_constraint_value = null): array|null
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);

        return $this->settings[$set_constraint_value];
    }

    /**
     * Save all changes back to the database.
     */
    protected function save(?string $custom_constraint_value = null): void
    {
        $set_constraint_value = $this->getConstraintValue($custom_constraint_value);
        $this->check($set_constraint_value);

        if ($this->dirty[$set_constraint_value]) {
            $update[$this->column] = $this->settings[$set_constraint_value];
            $constraint_query = $this->getConstraintQuery($set_constraint_value);

            \DB::table($this->table)
                ->whereRaw($constraint_query)
                ->update($update);

            $this->dirty[$set_constraint_value] = false;
        }

        $this->loaded[$set_constraint_value] = true;
    }

    /**
     * Load settings from the database.
     */
    public function load(string $set_constraint_value = null): void
    {
        $constraint_query = $this->getConstraintQuery($set_constraint_value);
        $json = \DB::table($this->table)
            ->whereRaw($constraint_query)
            ->value($this->column);

        $this->settings[$set_constraint_value] = json_decode($json, true);

        $this->dirty[$set_constraint_value] = false;
        $this->loaded[$set_constraint_value] = true;
    }

    /**
     * Check if settings have been loaded, load if not.
     */
    protected function check(string $set_constraint_value): void
    {
        if (empty($this->loaded[$set_constraint_value])) {
            $this->load($set_constraint_value);
        }
    }

    /**
     * Get constraint value; use custom if specified or default.
     */
    protected function getConstraintValue(?string $custom_constraint_value): ?string
    {
        return $custom_constraint_value ?: $this->default_constraint_value;
    }

    /**
     * Get constraint query.
     */
    protected function getConstraintQuery(string $set_constraint_value): string
    {
        return $this->default_constraint_key.' = '.$set_constraint_value;
    }
}
