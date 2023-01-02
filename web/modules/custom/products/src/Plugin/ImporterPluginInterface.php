<?php

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines an interface for Importer plugins.
 */
interface ImporterPluginInterface extends PluginInspectionInterface, PluginFormInterface, ConfigurableInterface
{
    /**
     * Performs the import. Returns TRUE if the import was successful or FALSE otherwise.
     *
     * @return bool
     */
    public function import();

    /**
     * Returns the Importer configuration entity.
     *
     * @return \Drupal\products\Entity\ImporterInterface
     */
    public function getConfig();
}
