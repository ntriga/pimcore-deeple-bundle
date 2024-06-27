<?php

namespace Ntriga\PimcoreDeepl\Actions;

use DeepL\DeepLException;
use DeepL\Translator;
use Exception;
use Pimcore\Model\DataObject\AbstractObject;

class TranslateEmptyLocalizedFieldsAction
{
    public function __construct(
        private readonly Translator $translator,
        private readonly GetGlossariesKeyedByNameAction $getGlossariesKeyedByNameAction,
        private readonly GetGlossaryNameAction $getGlossaryNameAction,
        private readonly string $sourceLang,
    ){}

    /**
     * @throws DeepLException
     * @throws Exception
     */
    public function __invoke(AbstractObject $object): void
    {
        $localizedFields = $object->getLocalizedfields();

        // Only continue if there are localized fields
        if ( !$localizedFields ) {
            return;
        }


//        $previousVersion = DataObject::getById($object->getId());
//        $previousLocalizedFields = $previousVersion->getLocalizedfields();
//        $previousItems = $previousLocalizedFields ? $previousLocalizedFields->getItems() : [];

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

                if( !$defaultLangValue ){
                    continue;
                }
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

                $object->set($fieldName, $translation->text, $localizedFieldsLang);
                $localizedFieldsItems[$localizedFieldsLang][$fieldName] = $translation->text;
            }
        }
    }
}
