<?php

namespace Yuraplohov\Setting;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/setting.php' => config_path('setting.php'),
            ], 'config');
        }

        $this->publishes([
            __DIR__.'/../database/migrations/create_settings_table.php' => $this->getMigrationFileName('create_settings_table.php'),
        ], 'migration');
    }

    /**
     * Returns existing migration file if found, else uses the name with current timestamp.
     *
     * @param string $migrationFileName
     * @return string
     */
    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath() . '/migrations/' . $timestamp . '_' . $migrationFileName)
            ->first();
    }
}
