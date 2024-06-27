<?php

namespace Ntriga\PimcoreDeepl\Actions;

use DeepL\DeepLException;
use DeepL\GlossaryEntries;
use DeepL\Translator;

class SyncGlossariesAction
{
    public function __construct(
        private readonly Translator $translator,
        private readonly array $localGlossary,
        private readonly string $sourceLang,
        private readonly array $targetLangs,
        private readonly GetGlossaryNameAction $getGlossaryNameAction,
    ){}

    /**
     * @throws DeepLException
     */
    public function __invoke(): void
    {
        $glossaries = $this->translator->listGlossaries();

        foreach( $this->targetLangs as $targetLang ){
            $glossaryName = ($this->getGlossaryNameAction)( $this->sourceLang, $targetLang);

            foreach( $glossaries as $glossary ){
                if( $glossaryName === $glossary->name ){
                    $this->translator->deleteGlossary( $glossary->glossaryId );
                }
            }

            $glossaryEntries = [];
            foreach( $this->localGlossary as $key => $entry) {
                if( isset($entry[$targetLang] ) ){
                    $glossaryEntries[$key] = $entry[$targetLang];
                }
            }
            if( empty($glossaryEntries) ){
                continue;
            }
            $glossaryEntries = GlossaryEntries::fromEntries($glossaryEntries);


            $this->translator->createGlossary($glossaryName, $this->sourceLang, $targetLang, $glossaryEntries);
        }
    }
}
