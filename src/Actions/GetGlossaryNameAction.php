<?php

namespace Ntriga\PimcoreDeepl\Actions;

class GetGlossaryNameAction
{
    public function __construct(
        private readonly string $glossaryPrefix,
    ){}

    public function __invoke(string $sourceLang, string$targetLang): string
    {
        return $this->glossaryPrefix.'_'.$sourceLang.'_'.$targetLang;
    }
}
