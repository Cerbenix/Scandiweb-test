<?php declare(strict_types=1);

namespace App\Models;

use App\Core\DatabaseConnector;
use Doctrine\DBAL\Exception;

class Furniture extends Product
{
    protected string $dimensions;

    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    public function save(): void
    {
        try {
            DatabaseConnector::getQueryBuilder()
                ->insert('products')
                ->values(
                    [
                        'sku' => '?',
                        'name' => '?',
                        'price' => '?',
                        'dimensions' => '?',
                        'type' => '?'
                    ]
                )
                ->setParameter(0, $this->getSku())
                ->setParameter(1, $this->getName())
                ->setParameter(2, $this->getPrice())
                ->setParameter(3, $this->getDimensions())
                ->setParameter(4, 'Furniture')
                ->executeQuery();
        } catch (Exception $exception) {
            http_response_code($exception->getCode());
        }
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['dimensions' => $this->dimensions]);
    }

    public function validate(): bool
    {
        $isValid = parent::validate();

        if (empty($this->dimensions) || !preg_match('/^\d+x\d+x\d+$/', $this->dimensions)) {
            $isValid = false;
        }

        return $isValid;
    }
}
