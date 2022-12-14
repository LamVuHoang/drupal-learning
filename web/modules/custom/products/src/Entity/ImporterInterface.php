<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

/**
 * Importer configuration entity.
 */
interface ImporterInterface extends ConfigEntityInterface
{
     // /**
     //  * Returns the Url where the import can get the data from.
     //  *
     //  * @return Url
     //  */
     //  This method is specific to JsonImporter only
     // public function getUrl();


     /**
      * Returns the Importer plugin ID to be used by this importer.
      *
      * @return string
      */
     public function getPluginId();


     /**
      * Whether or not to update existing products if they have already been imported.
      *
      * @return bool
      */
     public function updateExisting();


     /**
      * Returns the source of the products.
      *
      * @return string
      */
     public function getSource();

     /**
      * Returns the Product type that needs to be created.
      *
      * @return string
      */
     public function getBundle();

     /**
      * Returns the configuration specific to the chosen plugin.
      *
      * @return array
      */
     public function getPluginConfiguration();
     
     /**
      * Sets the plugin configuration.
      *
      * @param array $configuration
      * The plugin configuration.
      */
     public function setPluginConfiguration($configuration);
}
