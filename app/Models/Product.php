<?php declare(strict_types=1);

namespace App\Models;

use App\Core\DatabaseConnector;
use App\Exceptions\ProductNotFoundException;
use Doctrine\DBAL\Exception;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;

class Product implements JsonSerializable
{
    protected int $id;
    protected string $sku;
    protected string $name;
    protected float $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function save(): void
    {
    }

    public function delete(int $productId): void
    {
        try {
            DatabaseConnector::getConnection()
                ->delete('products', ['id' => $productId]);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getAll(): array
    {
        try {
            $productCollection = [];
            $products = DatabaseConnector::getQueryBuilder()
                ->select('*')
                ->from('products')
                ->fetchAllAssociative();
            foreach ($products as $product) {
                $productCollection[] = $this->buildModel($product['type'], $product);
            }
            return $productCollection;
        } catch (Exception|ProductNotFoundException $exception) {
            echo $exception->getMessage();
            return [];
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }

    public function buildModel(string $productType, array $variables): Product
    {
        $className = "App\\Models\\" . ucfirst(strtolower($productType));

        if (!class_exists($className)) {
            throw new ProductNotFoundException("Product type '$productType' not found.");
        }

        $reflectionClass = new ReflectionClass($className);
        try {
            $object = $reflectionClass->newInstanceWithoutConstructor();
        } catch (ReflectionException $exception) {
            echo "Error: Failed to create an instance of $className";
        }

        foreach ($variables as $propertyName => $value) {
            $setterMethod = 'set' . ucfirst($propertyName);
            if ($reflectionClass->hasMethod($setterMethod)) {
                $reflectionMethod = $reflectionClass->getMethod($setterMethod);
                try {
                    $reflectionMethod->invoke($object, $value);
                } catch (ReflectionException $exception) {
                    echo "Error: Failed to invoke setter method $setterMethod";
                }
            }
        }

        return $object;
    }

    public function validate(): bool
    {
        $isValid = true;

        if (empty($this->sku) || strlen($this->sku) < 8 || strlen($this->sku) > 12) {
            $isValid = false;
        }

        if (empty($this->name)) {
            $isValid = false;
        }

        if ($this->price <= 0) {
            $isValid = false;
        }

        return $isValid;
    }

}