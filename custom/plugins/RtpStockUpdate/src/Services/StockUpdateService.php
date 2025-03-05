<?php declare(strict_types=1);

namespace Rtp\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockUpdateService
{

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function updateStockExecute(OutputInterface $output): int
    {
        try {
            $lShopStockData = $this->fetchCsvFromFtp(
                $_ENV['LSHOP_SERVER'],
                $_ENV['LSHOP_STOCK_USER'],
                $_ENV['LSHOP_STOCK_PASSWORD'],
                'stockfile.csv'
            );

            if (!empty($lShopStockData)) {
                $this->setStockToZero();
                if ($this->updateStockData($lShopStockData) === 0) {
                    throw new RuntimeException('No products updated.');
                }
            }

            return Command::SUCCESS;

        } catch (RuntimeException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    public function setStockToZero(): void
    {
        $this->connection->createQueryBuilder()
            ->update('product')
            ->set('stock', 0)
            ->set('is_closeout', 1)
            ->set('available_stock', 0)
            ->set('available', 0)
            ->where('parent_id IS NOT NULL')
            ->executeStatement();
    }

    public function updateStockData(array $lshopData): int
    {
        $updated = 0;

        foreach ($lshopData as $row) {
            if (!isset($row[0], $row[1])) {
                continue;
            }

            $sku = $row[0];
            $stock = (int)$row[1];

            if ($stock > 0) {
                $qb = $this->connection->createQueryBuilder();
                $qb->update('product')
                    ->set('stock', ':stock')
                    ->set('is_closeout', '0')
                    ->set('available_stock', ':stock')
                    ->set('available', '1')
                    ->where('product_number = :sku')
                    ->setParameter('stock', $stock)
                    ->setParameter('sku', $sku);

                if ($qb->executeStatement() > 0) {
                    $updated++;
                }
            }
        }

        return $updated;
    }

    public function fetchCsvFromFtp(string $server, string $user, string $password, string $remoteFile, string $delimiter = ';'): array
    {
        $ftpConn = @ftp_connect($server);
        if (!$ftpConn || !@ftp_login($ftpConn, $user, $password)) {
            throw new RuntimeException('FTP connection failed.');
        }

        $tempFile = tmpfile();
        if (!$tempFile) {
            ftp_close($ftpConn);
            throw new RuntimeException('Failed to create temp file.');
        }

        $tempPath = stream_get_meta_data($tempFile)['uri'];
        if (!ftp_get($ftpConn, $tempPath, $remoteFile, FTP_ASCII)) {
            fclose($tempFile);
            ftp_close($ftpConn);
            throw new RuntimeException('Failed to download CSV file.');
        }

        $csvData = [];
        if (($handle = fopen($tempPath, 'rb')) !== false) {
            fgetcsv($handle); // Erste Zeile überspringen
            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        ftp_close($ftpConn);
        fclose($tempFile);

        return $csvData;
    }

}