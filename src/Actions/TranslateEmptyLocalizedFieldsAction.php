<?php

namespace Ntriga\PimcoreDeepl\Actions;

use DeepL\Translator;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Tool;

class TranslateEmptyLocalizedFieldsAction
{
    public function __construct(
        private readonly Translator $translator,
        private readonly GetGlossariesKeyedByNameAction $getGlossariesKeyedByNameAction,
        private readonly GetGlossaryNameAction $getGlossaryNameAction,
        private readonly string $sourceLang,
    ){}

    public function __invoke(AbstractObject $object): void
    {
        $localizedFields = $object->getLocalizedfields();

        // Only continue if there are localized fields
        if ( !$localizedFields ) {
            return;
        }

        $previousVersion = DataObject::getById($object->getId());
        $previousLocalizedFields = $previousVersion->getLocalizedfields();
        $previousItems = $previousLocalizedFields ? $previousLocalizedFields->getItems() : [];

        $glossaries = ($this->getGlossariesKeyedByNameAction)();

        $localizedFieldsItems = $localizedFields->getItems();
        foreach( $localizedFieldsItems as $localizedFieldsLang => $localizedFieldsItem ){
            if( $localizedFieldsLang === $this->sourceLang ){
                continue;
            }

            $glossaryName = ($this->getGlossaryNameAction)( $this->sourceLang, $localizedFieldsLang);
            $glossaryForLang = $glossaries[$glossaryName] ? $glossaries[$glossaryName]->glossaryId : null;

            foreach( $localizedFieldsItem as $fieldName => $fieldValue ){
                $defaultLangValue = $localizedFieldsItems[$this->sourceLang][$fieldName];
//                $previousDefaultLangValue = $previousItems[$this->sourceLang][$fieldName] ?? null;

                if( $fieldValue ){
                    continue;
                }


                $translation = $this->translator->translateText(
                    $defaultLangValue,
                    $this->sourceLang,
                    $localizedFieldsLang,
                    ['glossary' => $glossaryForLang],
                );
                $localizedFieldsItems[$localizedFieldsLang][$fieldName] = $translation;
            }
        }

        $localizedFields->setItems($localizedFieldsItems);
        $object->setLocalizedfields($localizedFields);
    }
}
