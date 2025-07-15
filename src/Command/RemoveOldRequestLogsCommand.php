<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sylius-analytics:remove-old',
    description: 'Removes analytics request logs older than a given number of days (default: 90).',
)]
final class RemoveOldRequestLogsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('days', InputArgument::OPTIONAL, 'Number of days to keep logs for (default: 90)', 90);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $daysArg = $input->getArgument('days');
        $days = is_numeric($daysArg) ? (int) $daysArg : 90;

        if ($days <= 0) {
            $output->writeln('<error>Days must be a positive integer.</error>');

            return Command::FAILURE;
        }

        $cutoffDate = new \DateTimeImmutable("-{$days} days");

        $output->writeln(sprintf('Removing logs older than %s...', $cutoffDate->format('Y-m-d H:i:s')));

        $query = $this->entityManager->createQuery(
            'DELETE FROM ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog rl WHERE rl.createdAt < :cutoff',
        )->setParameter('cutoff', $cutoffDate);

        $result = $query->execute();
        $deletedCount = is_int($result) ? $result : 0;

        $output->writeln(sprintf('<info>%d request log(s) deleted.</info>', $deletedCount));

        return Command::SUCCESS;
    }
}
