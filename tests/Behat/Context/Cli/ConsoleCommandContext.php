<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Cli;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLogInterface;
use Webmozart\Assert\Assert;

final class ConsoleCommandContext implements Context
{
    private ?CommandTester $commandTester = null;

    private ?int $exitCode = null;

    public function __construct(
        private KernelInterface $kernel,
        private EntityManagerInterface $entityManager,
        private ChannelRepositoryInterface $channelRepository,
    ) {
    }

    /**
     * @Given there are request logs from :days days ago
     */
    public function thereAreRequestLogsFromDaysAgo(int $days): void
    {
        // Get the default channel
        $channel = $this->channelRepository->findOneBy([]);
        Assert::notNull($channel, 'Default channel should exist for tests');

        $requestLog = $this->entityManager->getClassMetadata(RequestLogInterface::class)->newInstance();
        $requestLog->setUrl('https://example.com/test');
        $requestLog->setRouteName('sylius_shop_homepage');
        $requestLog->setChannel($channel);
        $requestLog->setSessionId('test-session-' . $days);
        $requestLog->setIpAddress('127.0.0.1');
        $requestLog->setUserAgent('Test User Agent');

        // Set the creation date to the specified number of days ago
        $createdAt = new \DateTimeImmutable("-{$days} days");
        $requestLog->setCreatedAt($createdAt);

        $this->entityManager->persist($requestLog);
        $this->entityManager->flush();
    }

    /**
     * @When I run the command :commandLine
     */
    public function iRunTheCommand(string $commandLine): void
    {
        $application = new Application($this->kernel);

        // Parse command line to separate command name and arguments
        $parts = explode(' ', trim($commandLine));
        $commandName = array_shift($parts);

        $command = $application->find($commandName);
        $this->commandTester = new CommandTester($command);

        // Build arguments array
        $args = [];
        if (!empty($parts)) {
            // For our command, the first argument is the 'days' argument
            $args['days'] = $parts[0];
        }

        $this->exitCode = $this->commandTester->execute($args);
    }

    /**
     * @Then the command should succeed
     */
    public function theCommandShouldSucceed(): void
    {
        Assert::eq($this->exitCode, 0, 'Command should have succeeded but returned exit code: ' . $this->exitCode);
    }

    /**
     * @Then the command should fail
     */
    public function theCommandShouldFail(): void
    {
        Assert::notEq($this->exitCode, 0, 'Command should have failed but succeeded');
    }

    /**
     * @Then logs older than :days days should be removed
     */
    public function logsOlderThanDaysShouldBeRemoved(int $days): void
    {
        // Check that logs older than the specified days have been removed
        $cutoffDate = new \DateTimeImmutable("-{$days} days");

        $repository = $this->entityManager->getRepository(RequestLogInterface::class);
        $oldLogs = $repository->createQueryBuilder('r')
            ->where('r.createdAt < :cutoff')
            ->setParameter('cutoff', $cutoffDate)
            ->getQuery()
            ->getResult();

        Assert::isEmpty($oldLogs, 'Old logs should have been removed');
    }

    /**
     * @Then recent logs should be preserved
     */
    public function recentLogsShouldBePreserved(): void
    {
        // Check that recent logs (within 90 days by default) are still there
        $cutoffDate = new \DateTimeImmutable('-90 days');

        $repository = $this->entityManager->getRepository(RequestLogInterface::class);
        $recentLogs = $repository->createQueryBuilder('r')
            ->where('r.createdAt >= :cutoff')
            ->setParameter('cutoff', $cutoffDate)
            ->getQuery()
            ->getResult();

        Assert::notEmpty($recentLogs, 'Recent logs should be preserved');
    }

    /**
     * @Then logs newer than :days days should be preserved
     */
    public function logsNewerThanDaysShouldBePreserved(int $days): void
    {
        // Check that logs newer than the specified days are preserved
        $cutoffDate = new \DateTimeImmutable("-{$days} days");

        $repository = $this->entityManager->getRepository(RequestLogInterface::class);
        $recentLogs = $repository->createQueryBuilder('r')
            ->where('r.createdAt >= :cutoff')
            ->setParameter('cutoff', $cutoffDate)
            ->getQuery()
            ->getResult();

        Assert::notEmpty($recentLogs, 'Recent logs should be preserved');
    }

    /**
     * @Then I should see an error about numeric value
     */
    public function iShouldSeeAnErrorAboutNumericValue(): void
    {
        Assert::notNull($this->commandTester, 'Command tester should be initialized');
        $output = $this->commandTester->getDisplay();
        Assert::contains($output, 'numeric', 'Error message should mention numeric value');
    }

    /**
     * @Then I should see an error about positive integer
     */
    public function iShouldSeeAnErrorAboutPositiveInteger(): void
    {
        Assert::notNull($this->commandTester, 'Command tester should be initialized');
        $output = $this->commandTester->getDisplay();
        Assert::contains($output, 'positive', 'Error message should mention positive integer');
    }
}
