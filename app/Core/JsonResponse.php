<?php declare(strict_types=1);

namespace App\Core;

class JsonResponse
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): string
    {
        return json_encode($this->data);
    }
}
