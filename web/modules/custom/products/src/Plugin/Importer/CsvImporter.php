<?php

namespace Drupal\products\Plugin\Importer;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\products\Plugin\ImporterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Product importer from a CSV format.
 *
 * @Importer(
 * id = "csv",
 * label = @Translation("CSV Importer")
 * )
 */
class CsvImporter extends ImporterBase
{
    use StringTranslationTrait;

    /**
     * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
     */
    protected $streamWrapperManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        EntityTypeManagerInterface $entityTypeManager,
        // MessengerInterface $messenger,
        StreamWrapperManagerInterface $streamWrapperManager
    ) {
        parent::__construct(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $entityTypeManager,
            // $messenger
        );
        $this->streamWrapperManager = $streamWrapperManager;
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
            // $container->get('messenger'),
            $container->get('stream_wrapper_manager')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function import()
    {
        $products = $this->getData();
        if (!$products) {
            return FALSE;
        }
        foreach ($products as $product) {
            $this->persistProduct($product);
        }
        return TRUE;
    }

    /**
     * Loads the product data from the CSV file.
     *
     * @return array
     */
    private function getData()
    {
        /** @var \Drupal\products\Entity\ImporterInterface $importer_config */
        $importer_config = $this->configuration['config'];
        $fids = $this->configuration['file'];
        if (!$fids) {
            return [];
        }
        $fid = reset($fids);
        /** @var \Drupal\file\FileInterface $file */
        $file = $this->entityTypeManager->getStorage('file')->load($fid);
        $wrapper = $this->streamWrapperManager->getViaUri($file->getFileUri());
        if (!$wrapper) {
            return [];
        }
        $url = $wrapper->realpath();
        $spl = new \SplFileObject($url, 'r');
        $data = [];
        while (!$spl->eof()) {
            $data[] = $spl->fgetcsv();
        }
        $products = [];
        $header = [];
        foreach ($data as $key => $row) {
            if ($key == 0) {
                $header = $row;
                continue;
            }
            if ($row[0] == "") {
                continue;
            }
            $product = new \stdClass();
            foreach ($header as $header_key => $label) {
                $product->{$label} = $row[$header_key];
            }
            $products[] = $product;
        }
        return $products;
    }

    private function persistProduct($product)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [
            'file' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(
        array $form,
        FormStateInterface $form_state
    ) {
        $form['file'] = [
            '#type' => 'managed_file',
            '#default_value' => $this->configuration['file'],
            '#title' => $this->t('File'),
            '#description' => $this->t('The CSV file containing the product records.'),
            '#required' => TRUE,
            '#upload_location' => 'public://',
            '#upload_validators' => [
                'file_validate_extensions' => ['csv'],
            ],
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(
        array &$form,
        FormStateInterface $form_state
    ) {
        $this->configuration['file'] = $form_state->getValue('file');
    }
}
