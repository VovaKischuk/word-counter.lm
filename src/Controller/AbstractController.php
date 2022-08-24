<?php

declare(strict_types=1);

namespace App\Controller;

use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbsController;

abstract class AbstractController extends AbsController
{
    /**
     * @throws JsonException
     */
    public function getRequestPayload(Request $request): array
    {
        return \json_decode(json: $request->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
    }
}
