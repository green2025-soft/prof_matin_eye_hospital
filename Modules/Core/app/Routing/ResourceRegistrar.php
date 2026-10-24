<?php

namespace Modules\Core\Routing;

use Illuminate\Routing\ResourceRegistrar as LaravelResourceRegistrar;

class ResourceRegistrar extends LaravelResourceRegistrar
{
    protected function getResourceAction($resource, $controller, $method, $options): array
    {
        $action = parent::getResourceAction($resource, $controller, $method, $options);

        // Preserve resource route names; both write routes use the shared save action.
        if ($method === 'update') {
            $action['uses'] = $controller.'@store';
        }

        return $action;
    }
}
