<?php

namespace App\Presentation\Dashboard\Controllers;

use App\Presentation\Shared\ApiResponse;
use App\Application\Dashboard\UseCases\GetDashboardStats;
use App\Presentation\Products\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Dashboard", description: "Dashboard statistics")]
class DashboardController
{
    use ApiResponse;

    #[OA\Get(
        path: "/dashboard/stats",
        summary: "Get dashboard statistics",
        description: "Returns summary counts and the latest products",
        tags: ["Dashboard"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Stats obtained successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Estadísticas del dashboard obtenidas correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "total_categories", type: "integer", example: 5),
                                new OA\Property(property: "total_products", type: "integer", example: 10),
                                new OA\Property(property: "total_stock", type: "integer", example: 150),
                                new OA\Property(
                                    property: "latest_products",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "string", format: "uuid", example: "01915645-b82d-7d88-b2ef-90c1df348981"),
                                            new OA\Property(property: "name", type: "string", example: "Camiseta Overskull"),
                                            new OA\Property(property: "description", type: "string", example: "Camiseta negra edición limitada"),
                                            new OA\Property(property: "price", type: "number", format: "float", example: 89.90),
                                            new OA\Property(property: "stock", type: "integer", example: 50),
                                            new OA\Property(property: "category_id", type: "string", format: "uuid", example: "01915645-a92c-7b44-a1ff-80c1df348981"),
                                            new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:00:00"),
                                            new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:00:00")
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function stats(GetDashboardStats $useCase): JsonResponse
    {
        $stats = $useCase->execute(5); // Show latest 5 products

        return $this->successResponse(
            'Estadísticas del dashboard obtenidas correctamente',
            [
                'total_categories' => $stats['total_categories'],
                'total_products' => $stats['total_products'],
                'total_stock' => $stats['total_stock'],
                'latest_products' => ProductResource::collection($stats['latest_products']),
            ]
        );
    }
}
