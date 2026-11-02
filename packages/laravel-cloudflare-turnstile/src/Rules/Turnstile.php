<?php

declare(strict_types=1);

namespace Eolica\LaravelCloudflareTurnstile\Rules;

use Closure;
use Eolica\CloudflareTurnstile\Exception\TurnstileException;
use Eolica\CloudflareTurnstile\TurnstileClient;
use Eolica\LaravelCloudflareTurnstile\Events\TokenVerified;
use Eolica\LaravelCloudflareTurnstile\VerifiedTokens;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use SensitiveParameter;

final class Turnstile implements ValidationRule
{
    public bool $implicit = true;

    public function __construct(
        private readonly ?string $action = null,
        private readonly ?bool $sendRemoteIp = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !$this->verify($value)) {
            $fail('turnstile::validation.failed')->translate();
        }
    }

    private function verify(#[SensitiveParameter] string $token): bool
    {
        $container = Container::getInstance();
        $container->make(VerifiedTokens::class)->add($token);

        try {
            $response = $container
                ->make(TurnstileClient::class)
                ->withExpectedAction($this->action)
                ->verify($token, remoteIp: $this->remoteIp($container));
        } catch (TurnstileException $e) {
            $container->make(ExceptionHandler::class)->report($e);

            return false;
        }

        $container->make(Dispatcher::class)->dispatch(new TokenVerified($response, $this->action));

        return $response->success;
    }

    private function remoteIp(Container $container): ?string
    {
        $sendRemoteIp = $this->sendRemoteIp ?? $container->make(Repository::class)->boolean('turnstile.send_remote_ip');

        if (!$sendRemoteIp) {
            return null;
        }

        return $container->make(Request::class)->ip();
    }
}
