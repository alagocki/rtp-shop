<?php declare(strict_types=1);

namespace Rtp\Command;

use Rtp\Services\StockUpdateService;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'rtp:stock:update',
    description: 'Updates product stock based on LShop data.',
)]
class StockUpdateCommand extends Command
{
    private StockUpdateService $stockUpdateService;

    public function __construct(StockUpdateService $stockUpdateService)
    {
        parent::__construct();
        $this->stockUpdateService = $stockUpdateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            if ($this->stockUpdateService->updateStockExecute($output) > 0) {
                throw new RuntimeException('Update failed.');
            }

            $output->writeln('<info>Stock update at ' . date('d.m.Y h:i:s') . ' completed successfully.</info>');
            return Command::SUCCESS;

        } catch (RuntimeException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
