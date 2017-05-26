<?php

namespace App\Routing;


interface IRoute
{
    public function getName(): string;

    public function getUrl(): string;

    public function getControllerSet(): array;

    public function getControllerName(): string;

    public function getControllerMethod(): string;

    public function getRequestMethod(): string;

    public function getType(): string;

	/**
     * Check if filter is enabled(according to route type)
     * @return bool
     */
    public function isFilterEnabled(): bool;

}