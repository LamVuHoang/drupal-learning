<?php

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\products\Entity\ImporterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for Importer plugins.
 */
abstract class ImporterBase extends PluginBase implements
    ImporterPluginInterface,
    ContainerFactoryPluginInterface
{
    use DependencySerializationTrait;
    
    /**
     * @var \Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        EntityTypeManager $entityTypeManager,
    ) {
        parent::__construct(
            $configuration, 
            $plugin_id, 
            $plugin_definition
        );
        $this->entityTypeManager = $entityTypeManager;

        if (!isset($configuration['config'])) {
            throw new PluginException('Missing Importer configuration.');
        }
        if (!$configuration['config'] instanceof ImporterInterface) {
            throw new PluginException('Wrong Importer configuration.');
        }

        // AJAX API
        $this->setConfiguration($configuration);
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
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->configuration['config'];
    }


    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(
        array $form,
        FormStateInterface $form_state
    ) {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfigurationForm(
        array &$form,
        FormStateInterface $form_state
    ) {
        // Do nothing by default.
    }
    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(
        array &$form,
        FormStateInterface $form_state
    ) {
        // Do nothing by default.
    }

    /**
     * @inheritDoc
     */
    public function defaultConfiguration()
    {
        return [];
    }
    /**
     * @inheritDoc
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    /**
     * {@inheritdoc}
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration + $this->defaultConfiguration();
    }
}
