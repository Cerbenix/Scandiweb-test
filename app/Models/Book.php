<?php declare(strict_types=1);

namespace App\Models;

use App\Core\DatabaseConnector;
use Doctrine\DBAL\Exception;

class Book extends Product
{
    protected string $weight;

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): void
    {
        $this->weight = $weight;
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
                        'weight' => '?',
                        'type' => '?'
                    ]
                )
                ->setParameter(0, $this->getSku())
                ->setParameter(1, $this->getName())
                ->setParameter(2, $this->getPrice())
                ->setParameter(3, $this->getWeight())
                ->setParameter(4, 'Book')
                ->executeQuery();
        } catch (Exception $exception) {
            http_response_code($exception->getCode());
        }
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['weight' => $this->weight]);
    }

    public function validate(): bool
    {
        $isValid = parent::validate();

        if (empty($this->weight) || !preg_match('/^\d+(\.\d+)?kg$/', $this->weight)) {
            $isValid = false;
        }

        return $isValid;
    }
}
