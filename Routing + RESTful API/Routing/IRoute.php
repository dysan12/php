<?php

namespace Webqms\Routing;


interface IRoute
{
    public function getName(): string;

    public function getUrl(): string;

    public function getControllerSet(): array;

    public function getControllerName(): string;

    public function getControllerMethod(): string;

    public function getRequestMethod(): string;

    public function getType(): string;

    public function isFilterEnabled(): bool;

}