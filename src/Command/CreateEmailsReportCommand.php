<?php

namespace App\Command;

use App\Service\CreateFullReport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateEmailsReportCommand extends Command
{
    protected static $defaultName = 'app:create-emails-report';

    /**
     * @var ParameterBagInterface $params
     */
    private $params;

    /**
     * @var CreateFullReport $createFullReport
     */
    private $createFullReport;

    /**
     * ValidateEmailsCommand constructor.
     * @param ParameterBagInterface $params
     * @param CreateFullReport $createFullReport
     */
    public function __construct(ParameterBagInterface $params, CreateFullReport $createFullReport)
    {
        $this->params = $params;
        $this->createFullReport = $createFullReport;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Commands validate email addresses provided by a given file')
            ->addArgument('path', InputArgument::OPTIONAL, 'path to the file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');

        try {
            $fullPath  = sprintf('%s%s', $this->params->get('kernel.project_dir'), $path);

            if (!$fileExists = file_exists($fullPath)) {
                throw new \InvalidArgumentException('Given file does not exists: %s ', $path);
            }
            if (!$fileContent = file($fullPath, FILE_SKIP_EMPTY_LINES)) {
                throw new \InvalidArgumentException('Given file is empty: %s ', $path);
            }

            $this->createFullReport->create($fileContent);
        } catch(\Exception $e) {
            return $output->writeln(sprintf('<error>Invalid argument: %s %s', $e->getMessage(), '</error>'));
        }
        $io->success('Report created successfully');
    }
}
