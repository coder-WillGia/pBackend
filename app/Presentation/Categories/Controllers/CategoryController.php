<?php

namespace App\Presentation\Categories\Controllers;

use App\Presentation\Shared\ApiResponse;
use App\Application\Categories\UseCases\CreateCategory;
use App\Application\Categories\UseCases\GetCategories;
use App\Application\Categories\UseCases\GetCategory;
use App\Application\Categories\UseCases\UpdateCategory;
use App\Application\Categories\UseCases\DeleteCategory;
use App\Presentation\Categories\Requests\StoreCategoryRequest;
use App\Presentation\Categories\Requests\UpdateCategoryRequest;
use App\Presentation\Categories\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Categories", description: "Operations related to Categories")]
class CategoryController
{
    use ApiResponse;

    #[OA\Get(
        path: "/categories",
        summary: "List all categories",
        description: "Returns a list of all categories",
        tags: ["Categories"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of categories retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Categorías obtenidas correctamente"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Ropa"),
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
    public function index(GetCategories $useCase): JsonResponse
    {
        $categories = $useCase->execute();
        return $this->successResponse(
            'Categorías obtenidas correctamente',
            CategoryResource::collection($categories)
        );
    }

    #[OA\Post(
        path: "/categories",
        summary: "Create a new category",
        description: "Creates a category and returns the created resource",
        tags: ["Categories"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Electrónica", maxLength: 100)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Categoría creada correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "name", type: "string", example: "Electrónica"),
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
                                new OA\Property(property: "name", type: "array", items: new OA\Items(type: "string", example: "Ya existe una categoría con este nombre."))
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(StoreCategoryRequest $request, CreateCategory $useCase): JsonResponse
    {
        $category = $useCase->execute($request->validated('name'));
        return $this->successResponse(
            'Categoría creada correctamente',
            new CategoryResource($category),
            201
        );
    }

    #[OA\Get(
        path: "/categories/{id}",
        summary: "Get category by ID",
        description: "Returns a single category",
        tags: ["Categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Categoría obtenida correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Ropa"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:00:00")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found",
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
    public function show(int $id, GetCategory $useCase): JsonResponse
    {
        $category = $useCase->execute($id);
        if (!$category) {
            return $this->errorResponse('Recurso no encontrado', null, 404);
        }
        return $this->successResponse(
            'Categoría obtenida correctamente',
            new CategoryResource($category)
        );
    }

    #[OA\Put(
        path: "/categories/{id}",
        summary: "Update category",
        description: "Updates an existing category and returns it",
        tags: ["Categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Calzado", maxLength: 100)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Categoría actualizada correctamente"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Calzado"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-08-15 10:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-08-15 10:10:00")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found",
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
                                new OA\Property(property: "name", type: "array", items: new OA\Items(type: "string", example: "Ya existe una categoría con este nombre."))
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(UpdateCategoryRequest $request, int $id, UpdateCategory $useCase): JsonResponse
    {
        $category = $useCase->execute($id, $request->validated('name'));
        if (!$category) {
            return $this->errorResponse('Recurso no encontrado', null, 404);
        }
        return $this->successResponse(
            'Categoría actualizada correctamente',
            new CategoryResource($category)
        );
    }

    #[OA\Delete(
        path: "/categories/{id}",
        summary: "Delete category",
        description: "Deletes a category if it doesn't have associated products",
        tags: ["Categories"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Categoría eliminada correctamente"),
                        new OA\Property(property: "data", type: "null", example: null)
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad Request (e.g. category has associated products)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "No se puede eliminar la categoría porque tiene productos asociados."),
                        new OA\Property(property: "errors", type: "null")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Categoría no encontrada."),
                        new OA\Property(property: "errors", type: "null")
                    ]
                )
            )
        ]
    )]
    public function destroy(int $id, DeleteCategory $useCase): JsonResponse
    {
        $useCase->execute($id);
        return $this->successResponse('Categoría eliminada correctamente');
    }
}
