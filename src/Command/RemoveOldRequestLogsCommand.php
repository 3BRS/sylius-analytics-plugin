<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepositoryInterface;

#[AsCommand(
    name: 'sylius-analytics:remove-old',
    description: 'Removes analytics request logs older than a given number of days (default: 90).',
)]
class RemoveOldRequestLogsCommand extends Command
{
    public function __construct(
        private readonly RequestLogRepositoryInterface $requestLogRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('days', InputArgument::OPTIONAL, 'Number of days to keep logs for (default: 90)', 90);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $daysArg = $input->getArgument('days');

        if (!is_numeric($daysArg)) {
            $io->error('The "days" argument must be a numeric value.');

            return Command::FAILURE;
        }

        $days = (int) $daysArg;

        if ($days <= 0) {
            $io->error('The number of days must be a positive integer.');

            return Command::FAILURE;
        }

        $cutoffDate = new \DateTimeImmutable("-{$days} days");

        $io->section(sprintf('Removing logs older than %s...', $cutoffDate->format('Y-m-d H:i:s')));

        $deletedCount = $this->requestLogRepository->removeOlderThan($cutoffDate);

        $io->success(sprintf('%d request log(s) deleted.', $deletedCount));

        return Command::SUCCESS;
    }
}
