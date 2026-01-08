<?php

namespace App\Support\Context;

use Illuminate\Http\Request;

class ScopeContext
{
    /**
     * Chave usada pelo middleware SetScope
     */
    public const SESSION_KEY = 'scope';

    /**
     * Compatibilidade com versões antigas (se existir)
     */
    public const LEGACY_SESSION_KEY = 'amazing.scope';

    private const SCOPE_REGEX = '/^[a-z0-9_-]{1,64}$/i';

    public function __construct(private readonly ?Request $request = null) {}

    public function isValid(string $scope): bool
    {
        return $scope !== '' && (bool) preg_match(self::SCOPE_REGEX, $scope);
    }

    private function defaultScope(): string
    {
        return (string) config('amazing.default_scope', 'default');
    }

    public function set(string $scope): void
    {
        if (!$this->isValid($scope)) {
            $scope = $this->defaultScope();
        }

        session()->put(self::SESSION_KEY, $scope);
        session()->put(self::LEGACY_SESSION_KEY, $scope); // compat
    }

    public function get(): string
    {
        $scope = (string) session()->get(self::SESSION_KEY, '');

        if ($scope !== '' && $this->isValid($scope)) {
            return $scope;
        }

        // compat: migra legacy key se existir
        $legacy = (string) session()->get(self::LEGACY_SESSION_KEY, '');
        if ($legacy !== '' && $this->isValid($legacy)) {
            session()->put(self::SESSION_KEY, $legacy);
            return $legacy;
        }

        return $this->defaultScope();
    }

    /**
     * Alias pra quem usa ->current()
     */
    public function current(): string
    {
        // se tiver request e tiver scope na rota, prioriza
        if ($this->request) {
            $fromRoute = (string) $this->request->route('scope', '');
            if ($fromRoute !== '' && $this->isValid($fromRoute)) {
                return $fromRoute;
            }
        }

        return $this->get();
    }
}
