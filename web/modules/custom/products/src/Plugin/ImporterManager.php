<?php

namespace Drupal\products\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides the Importer plugin manager.
 */
class ImporterManager extends DefaultPluginManager
{
    /**
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManagerInterface;

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
        ModuleHandlerInterface $module_handler,
        EntityTypeManagerInterface $entityTypeManagerInterface
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
        $this->entityTypeManagerInterface = $entityTypeManagerInterface;
    }

    public function createInstanceFromConfig($id)
    {
        $config = $this->entityTypeManagerInterface->getStorage('importer')->load($id);
        $configuration = ['config' => $config] + $config->getPluginConfiguration();
        if (!$config instanceof \Drupal\products\Entity\ImporterInterface) {
            return NULL;
        }
        return $this->createInstance(
            $config->getPluginId(),
            $configuration
        );
    }

    public function createInstanceFromAllConfigs()
    {
        $configs = $this->entityTypeManagerInterface->getStorage('importer')->loadMultiple();
        if (!$configs) {
            return [];
        }
        $plugins = [];
        foreach ($configs as $config) {
            $plugin = $this->createInstanceFromConfig($config->id());
            if (!$plugin) {
                continue;
            }
            $plugins[] = $plugin;
        }
        return $plugins;
    }
}
