<?php

namespace App\Support\Context;

class ScopeContext
{
    public function defaultScope(): string
    {
        return (string) config('amazing.default_scope', 'default');
    }

    public function get(): string
    {
        if (app()->bound('currentScope')) {
            return (string) app('currentScope');
        }

        $scope = (string) session('scope', $this->defaultScope());

        return $scope !== '' ? $scope : $this->defaultScope();
    }

    public function set(string $scope): string
    {
        $scope = $scope !== '' ? $scope : $this->defaultScope();

        session()->put('scope', $scope);
        app()->instance('currentScope', $scope);
        view()->share('currentScope', $scope);

        return $scope;
    }

    public function isValid(string $scope): bool
    {
        return $scope !== '' && (bool) preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $scope);
    }
}
