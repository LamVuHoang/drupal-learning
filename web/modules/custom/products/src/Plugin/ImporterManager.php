<?php

namespace Drupal\products\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Importer plugin manager.
 */
class ImporterManager extends DefaultPluginManager
{
    /**
     * ImporterManager constructor.
     *
     * @param \Traversable $namespaces
     * An object that implements \Traversable which containsthe root paths
     * keyed by the corresponding namespace to look for pluginimplementations.
     * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
     * Cache backend instance to use.
     * @param \Drupal\Core\Extension\ModuleHandlerInterface$module_handler
     * The module handler to invoke the alter hook with.
     */
    public function __construct(
        \Traversable $namespaces,
        CacheBackendInterface $cache_backend,
        ModuleHandlerInterface $module_handler
    ) {
        parent::__construct(
            'Plugin/Importer',
            $namespaces,
            $module_handler,
            'Drupal\products\Plugin\ImporterPluginInterface',
            'Drupal\products\Annotation\Importer'
        );
        $this->alterInfo('products_importer_info');
        $this->setCacheBackend($cache_backend, 'products_importer_plugins');
    }

    public function createInstanceFromConfig($id)
    {
        $config = $this->entityTypeManager->getStorage('importer')->load($id);
        if (!$config instanceof \Drupal\products\Entity\ImporterInterface) {
            return NULL;
        }
        return $this->createInstance(
            $config->getPluginId(),
            ['config' => $config]
        );
    }
}
