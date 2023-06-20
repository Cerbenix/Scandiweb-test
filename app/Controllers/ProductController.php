<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\JsonResponse;
use App\Models\Product;
use Exception;

class ProductController
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(): JsonResponse
    {
        return new JsonResponse($this->product->getAll());
    }

    public function store(): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $product = $this->product->buildModel($data['type'], $data);

            if (!$product->validate()) {
                http_response_code(422);
                return new JsonResponse(['message' => 'Validation failed']);
            }

            $product->save();
            http_response_code(200);
            return new JsonResponse(['message' => 'Product saved successfully']);
        } catch (Exception $exception) {
            http_response_code($exception->getCode());
            return new JsonResponse(['message' => $exception->getMessage()]);
        }
    }

    public function delete(): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'));

        try {
            foreach ($data as $productId){
                $this->product->delete($productId);
            }
            http_response_code(204);
            return new JsonResponse(['message' => 'Products deleted successfully']);
        } catch (Exception $exception) {
            http_response_code($exception->getCode());
            return new JsonResponse(['message' => $exception->getMessage()]);
        }
    }
}
