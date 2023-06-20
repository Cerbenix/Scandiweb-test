<?php declare(strict_types=1);

return [
    ['GET', '/api/products', [\App\Controllers\ProductController::class, 'index']],
    ['DELETE', '/api/products', [\App\Controllers\ProductController::class, 'delete']],
    ['POST', '/api/products', [\App\Controllers\ProductController::class, 'store']],
];