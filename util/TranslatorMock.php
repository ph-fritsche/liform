<?php
namespace Pitch\Liform;

use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatorMock implements TranslatorInterface
{
    public function trans(
        string $id,
        array $parameters = [],
        ?string $domain = null,
        ?string $locale = null
    ): string {
        return $id . '-translated';
    }
}
