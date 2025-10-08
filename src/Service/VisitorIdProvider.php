<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class VisitorIdProvider implements VisitorIdProviderInterface
{
    private NativePasswordHasher | null $nativePasswordHasher = null;

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
        private readonly string | PasswordHasherAwareInterface | null $passwordHasherName = null,
    ) {
    }

    public function getVisitorIdFromRequest(Request $request): string
    {
        return $this->getHasher()->hash($request->getSession()->getId());
    }

    private function getHasher(): PasswordHasherInterface
    {
        return $this->passwordHasherName === null
            ? $this->getNativePasswordHasher()
            : $this->passwordHasherFactory->getPasswordHasher($this->passwordHasherName);
    }

    private function getNativePasswordHasher(): NativePasswordHasher
    {
        if ($this->nativePasswordHasher === null) {
            $this->nativePasswordHasher = new NativePasswordHasher();
        }

        return $this->nativePasswordHasher;
    }
}
