<?php declare(strict_types=1);

namespace App\Models;

use App\Core\DatabaseConnector;
use Doctrine\DBAL\Exception;

class Dvd extends Product
{
    protected string $size;

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
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
                        'size' => '?',
                        'type' => '?'
                    ]
                )
                ->setParameter(0, $this->getSku())
                ->setParameter(1, $this->getName())
                ->setParameter(2, $this->getPrice())
                ->setParameter(3, $this->getSize())
                ->setParameter(4, 'dvd')
                ->executeQuery();
        } catch (Exception $exception) {
            http_response_code($exception->getCode());
        }
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['size' => $this->size]);
    }

    public function validate(): bool
    {
        $isValid = parent::validate();

        if (empty($this->size) || !preg_match('/^\d+mb$/', $this->size)) {
            $isValid = false;
        }

        return $isValid;
    }
}
