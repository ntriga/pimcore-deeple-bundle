<?php

namespace Ntriga\PimcoreDeepl\Commands;

use Ntriga\PimcoreDeepl\Actions\SyncGlossariesAction;
use DeepL\DeepLException;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'datajane:deepl:sync-glossary'
)]
class SyncGlossaryCommand extends AbstractCommand
{
    public function __construct(
        private readonly SyncGlossariesAction $syncGlossariesAction,
    )
    {
        parent::__construct();
    }

    /**
     * @throws DeepLException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Syncing glossaries...');
        ($this->syncGlossariesAction)();
        $output->writeln('Glossaries synced.');

        return self::SUCCESS;
    }
}
