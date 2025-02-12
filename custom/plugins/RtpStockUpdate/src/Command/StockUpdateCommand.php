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
            $lShopStockData = $this->stockUpdateService->fetchCsvFromFtp(
                $_ENV['LSHOP_SERVER'],
                $_ENV['LSHOP_STOCK_USER'],
                $_ENV['LSHOP_STOCK_PASSWORD'],
                'stockfile.csv'
            );

            if (!empty($lShopStockData)) {
                $this->stockUpdateService->setStockToZero();
                if ($this->stockUpdateService->updateStockData($lShopStockData) === 0) {
                    throw new RuntimeException('No products updated.');
                }
            }

            $output->writeln('<info>Stock update completed successfully.</info>');
            return Command::SUCCESS;

        } catch (RuntimeException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
