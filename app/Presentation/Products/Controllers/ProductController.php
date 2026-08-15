<?php

namespace App\Presentation\Products\Controllers;

use App\Presentation\Shared\ApiResponse;
use App\Application\Products\UseCases\CreateProduct;
use App\Application\Products\UseCases\GetProducts;
use App\Application\Products\UseCases\GetProduct;
use App\Application\Products\UseCases\UpdateProduct;
use App\Application\Products\UseCases\DeleteProduct;
use App\Presentation\Products\Requests\StoreProductRequest;
use App\Presentation\Products\Requests\UpdateProductRequest;
use App\Presentation\Products\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Products", description: "Operations related to Products")]
class ProductController
{
    use ApiResponse;

    #[OA\Get(
        path: "/products",
        summary: "List all products",
        description: "Returns a list of all products",
        tags: ["Products"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of products retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Productos obtenidos correctamente"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Camiseta Overskull"),
                                    new OA\Property(property: "description", type: "string", example: "Camiseta negra edición limitada"),
                                    new OA\Property(property: "price", type: "number", format: "float", example: 89.90),
                                    new OA\Property(property: "stock", type: "integer", example: 50),
                                    new OA\Property(property: "category_id", type: "integer", example: 1),
                                    new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:00:00"),
                                    new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:00:00")
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function index(\Illuminate\Http\Request $request, GetProducts $useCase): JsonResponse
    {
        $perPage = $request->query('per_page') ? (int) $request->query('per_page') : null;
        $page = (int) $request->query('page', 1);

        $result = $useCase->execute($perPage, $page);

        if ($perPage !== null) {
            return $this->successResponse(
                'Productos obtenidos correctamente',
                [
                    'items' => ProductResource::collection($result['items']),
                    'meta' => $result['meta']
                ]
            );
        }

        return $this->successResponse(
            'Productos obtenidos correctamente',
            ProductResource::collection($result)
        );
    }

    #[OA\Post(
        path: "/products",
        summary: "Create a new product",
        description: "Creates a product and returns the created resource",
        tags: ["Products"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "price", "stock", "category_id"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Camiseta Overskull"),
                    new OA\Property(property: "description", type: "string", example: "Camiseta negra edición limitada", nullable: true),
                    new OA\Property(property: "price", type: "number", format: "float", example: 89.90),
                    new OA\Property(property: "stock", type: "integer", example: 50),
                    new OA\Property(property: "category_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Product created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Producto creado correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Camiseta Overskull"),
                                new OA\Property(property: "description", type: "string", example: "Camiseta negra edición limitada"),
                                new OA\Property(property: "price", type: "number", format: "float", example: 89.90),
                                new OA\Property(property: "stock", type: "integer", example: 50),
                                new OA\Property(property: "category_id", type: "integer", example: 1),
                                new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:05:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:05:00")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation errors",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Los datos proporcionados no son válidos"),
                        new OA\Property(
                            property: "errors",
                            properties: [
                                new OA\Property(property: "price", type: "array", items: new OA\Items(type: "string", example: "El precio debe ser mayor que 0."))
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(StoreProductRequest $request, CreateProduct $useCase): JsonResponse
    {
        $product = $useCase->execute(
            $request->validated('name'),
            $request->validated('description'),
            (float) $request->validated('price'),
            (int) $request->validated('stock'),
            (string) $request->validated('category_id')
        );
        return $this->successResponse(
            'Producto creado correctamente',
            new ProductResource($product),
            201
        );
    }

    #[OA\Get(
        path: "/products/{id}",
        summary: "Get product by ID",
        description: "Returns a single product",
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the product",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid", example: "01915645-b82d-7d88-b2ef-90c1df348981")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Producto obtenido correctamente"),
                        new OA\Property(
                            property: "data",
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
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Recurso no encontrado"),
                        new OA\Property(property: "errors", type: "null")
                    ]
                )
            )
        ]
    )]
    public function show(string $id, GetProduct $useCase): JsonResponse
    {
        $product = $useCase->execute($id);
        if (!$product) {
            return $this->errorResponse('Recurso no encontrado', null, 404);
        }
        return $this->successResponse(
            'Producto obtenido correctamente',
            new ProductResource($product)
        );
    }

    #[OA\Put(
        path: "/products/{id}",
        summary: "Update product",
        description: "Updates an existing product and returns it",
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the product",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid", example: "01915645-b82d-7d88-b2ef-90c1df348981")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "price", "stock", "category_id"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Camiseta Overskull Premium"),
                    new OA\Property(property: "description", type: "string", example: "Camiseta negra algodón peruano", nullable: true),
                    new OA\Property(property: "price", type: "number", format: "float", example: 99.90),
                    new OA\Property(property: "stock", type: "integer", example: 45),
                    new OA\Property(property: "category_id", type: "string", format: "uuid", example: "01915645-a92c-7b44-a1ff-80c1df348981")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Product updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Producto actualizado correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "string", format: "uuid", example: "01915645-b82d-7d88-b2ef-90c1df348981"),
                                new OA\Property(property: "name", type: "string", example: "Camiseta Overskull Premium"),
                                new OA\Property(property: "description", type: "string", example: "Camiseta negra algodón peruano"),
                                new OA\Property(property: "price", type: "number", format: "float", example: 99.90),
                                new OA\Property(property: "stock", type: "integer", example: 45),
                                new OA\Property(property: "category_id", type: "string", format: "uuid", example: "01915645-a92c-7b44-a1ff-80c1df348981"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:10:00")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Recurso no encontrado"),
                        new OA\Property(property: "errors", type: "null")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation errors",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Los datos proporcionados no son válidos"),
                        new OA\Property(
                            property: "errors",
                            properties: [
                                new OA\Property(property: "category_id", type: "array", items: new OA\Items(type: "string", example: "La categoría seleccionada no existe."))
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(UpdateProductRequest $request, string $id, UpdateProduct $useCase): JsonResponse
    {
        $product = $useCase->execute(
            $id,
            $request->validated('name'),
            $request->validated('description'),
            (float) $request->validated('price'),
            (int) $request->validated('stock'),
            (string) $request->validated('category_id')
        );
        if (!$product) {
            return $this->errorResponse('Recurso no encontrado', null, 404);
        }
        return $this->successResponse(
            'Producto actualizado correctamente',
            new ProductResource($product)
        );
    }

    #[OA\Delete(
        path: "/products/{id}",
        summary: "Delete product",
        description: "Deletes a product by ID",
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the product",
                required: true,
                schema: new OA\Schema(type: "string", format: "uuid", example: "01915645-b82d-7d88-b2ef-90c1df348981")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Producto eliminado correctamente"),
                        new OA\Property(property: "data", type: "null", example: null)
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Producto no encontrado."),
                        new OA\Property(property: "errors", type: "null")
                    ]
                )
            )
        ]
    )]
    public function destroy(string $id, DeleteProduct $useCase): JsonResponse
    {
        $useCase->execute($id);
        return $this->successResponse('Producto eliminado correctamente');
    }
}
