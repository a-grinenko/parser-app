<?php

namespace App\Command;

use Common\Parser\Builder\UserBuilder;
use Common\Parser\Service\FileIterator\FileIterator;
use Common\Parser\ValueObject\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'parse:files',
)]
class ParseFileCommand extends Command
{
    private const string DATA_DIR = __DIR__ . '/../../data/';

    public function __construct(
        private readonly FileIterator $fileIterator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::OPTIONAL, 'Name of the file in data/', 'examples.csv');
        $this->addOption('skip-first-row', null, InputOption::VALUE_NEGATABLE, 'Skip the first row of the file', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $file = new File(self::DATA_DIR . $input->getArgument('file'));

            foreach (
                $this->fileIterator->iterate(
                    file: $file,
                    skipFirstRow: (bool)$input->getOption('skip-first-row'),
                    builderClass: UserBuilder::class
                ) as $importedRow
            ) {
                // TODO: Consider implementing JSON file export. Console output is sufficient for current demonstration purposes.
                echo json_encode($importedRow->jsonSerialize(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
            }
        } catch (\Throwable $e) {
            // TODO: Implement normal error logger
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
