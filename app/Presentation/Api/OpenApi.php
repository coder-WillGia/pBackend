<?php

namespace App\Presentation\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Technical Test API Documentation",
    description: "Documentation for the technical test backend API"
)]
#[OA\Server(
    url: "/api",
    description: "Default API Server"
)]
#[OA\PathItem(
    path: "/health"
)]
class OpenApi
{
}
