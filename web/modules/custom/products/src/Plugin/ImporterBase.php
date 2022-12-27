<?php

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\products\Entity\ImporterInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Importer plugins.
 */
abstract class ImporterBase extends PluginBase implements
    ImporterPluginInterface,
    ContainerFactoryPluginInterface
{
    /**
     * @var \Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;


    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \Drupal\products\Plugin\ImporterManager
     */
    protected $_importerManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        EntityTypeManager $entityTypeManager,
        Client $httpClient,
        ImporterManager $importerManager
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->entityTypeManager = $entityTypeManager;
        $this->httpClient = $httpClient;
        $this->_importerManager = $importerManager;
        if (!isset($configuration['config'])) {
            throw new PluginException('Missing Importer configuration.');
        }
        if (!$configuration['config'] instanceof ImporterInterface) {
            throw new PluginException('Wrong Importer configuration.');
        }
    }


    /**
     * {@inheritdoc}
     */
    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('entity_type.manager'),
            $container->get('http_client'),
            $container->get('products.importer_manager')
        );
    }
}
