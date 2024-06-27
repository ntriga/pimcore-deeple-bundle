<?php

namespace Ntriga\PimcoreDeepl\Actions;

use DeepL\Translator;

class GetGlossariesKeyedByNameAction
{
    public function __construct(
        private readonly Translator $translator,
    ){}

    public function __invoke(): array
    {
        $glossaries = $this->translator->listGlossaries();
        $glossariesKeyedByName = [];
        foreach( $glossaries as $glossary ){
            $glossariesKeyedByName[$glossary->name] = $glossary;
        }
        return $glossariesKeyedByName;
    }
}
