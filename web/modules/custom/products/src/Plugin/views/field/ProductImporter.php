<?php

namespace Drupal\products\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\products\Plugin\ImporterManager;

/**
 * Field plugin that renders data about the Importer that
imported the Product.
 *
 * @ViewsField("product_importer")
 */
class ProductImporter extends FieldPluginBase
{

    /**
     * @var Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;

    /**
     * @var Drupal\products\Plugin\ImporterManager
     */
    protected $importerManager;

    public function __construct(
        EntityTypeManager $entityTypeManager,
        ImporterManager $importerManager
    ) {
        $this->entityTypeManager = $entityTypeManager;
        $this->importerManager = $importerManager;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ResultRow $values)
    {
        /** @var \Drupal\products\Entity\ProductInterface $product */
        $product = $values->_entity;
        $source = $product->getSource();
        $importers = $this->entityTypeManager->getStorage('importer')->loadByProperties([
            'source' => $source
        ]);
        if (!$importers) {
            return NULL;
        }
        // We'll assume one importer per source.
        /** @var \Drupal\products\Entity\ImporterInterface $importer
         */
        $importer = reset($importers);
        // return $this->sanitizeValue($importer->label());

        // If we want to show the entity label.
        if ($this->options['importer'] == 'entity') {
            return $this->sanitizeValue($importer->label());
        }
        // Otherwise we show the plugin label.
        $definition = $this->importerManager
            ->getDefinition($importer->getPluginId());
        return $this->sanitizeValue($definition['label']);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        // Leave empty to avoid a query on this field.
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        $options = parent::defineOptions();
        $options['importer'] = ['default' => 'entity'];
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildOptionsForm(
        &$form,
        FormStateInterface $form_state
    ) {
        $form['importer'] = [
            '#type' => 'select',
            '#title' => $this->t('Importer'),
            '#description' => $this->t('Which importer label to use?'),
            '#options' => [
                'entity' => $this->t('Entity'),
                'plugin' => $this->t('Plugin')
            ],
            '#default_value' => $this->options['importer'],
        ];
        parent::buildOptionsForm($form, $form_state);
    }
}
