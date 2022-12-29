<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Product bundle interface.
 */
interface ProductTypeInterface extends ConfigEntityInterface
{
    /**
     * Returns the Product type that needs to be created.
     *
     * @return string
     */
    public function getBundle();
}
