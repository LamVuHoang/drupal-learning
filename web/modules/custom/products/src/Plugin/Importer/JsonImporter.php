<?php

namespace Drupal\products\Plugin\Importer;

use Drupal\products\Plugin\ImporterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Product importer from a JSON format.
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("JSON Importer")
 * )
 */
class JsonImporter extends ImporterBase
{
    use StringTranslationTrait;
    /**
     * {@inheritdoc}
     */
    public function import()
    {
        $data = $this->getData();

        if (!$data) {
            return FALSE;
        }

        if (!isset($data->products)) {
            return FALSE;
        }

        $products = $data->products;

        foreach ($products as $product) {
            $this->persistProduct($product);
        }

        return TRUE;
    }

    /**
     * Loads the product data from the remote URL.
     *
     * @return \stdClass
     */
    private function getData()
    {
        /** 
         * @var \Drupal\products\Entity\ImporterInterface $config
         */

        //  Should have Error Handling
        $request = $this->httpClient->get($this->configuration['url']);
        $string = $request->getBody()->getContents();
        return json_decode($string);
    }

    /**
     * Saves a Product entity from the remote data.
     *
     * @param \stdClass $data
     */
    private function persistProduct($data)
    {
        /** 
         * @var \Drupal\products\Entity\ImporterInterface $config
         */
        $config = $this->configuration['config'];

        $existing = $this->entityTypeManager->getStorage('product')->loadByProperties([
            'remote_id' => $data->id,
            'source' => $config->getSource()
        ]);

        if (!$existing) {
            $values = [
                'remote_id' => $data->id,
                'source' => $config->getSource(),
                'type' => $config->getBundle(),
            ];

            /** 
             * @var \Drupal\products\Entity\ProductInterface $product 
             */
            $product = $this->entityTypeManager->getStorage('product')->create($values);
            $product->setName($data->name);
            $product->setProductNumber($data->number);
            $product->save();
            return;
        }

        if (!$config->updateExisting()) {
            return;
        }
        /** 
         * @var \Drupal\products\Entity\ProductInterface $product
         */
        $product = reset($existing);
        $product->setName($data->name);
        $product->setProductNumber($data->number);
        $product->save();
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [
            'url' => '',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(
        array $form,
        FormStateInterface $form_state
    ) {
        $form['url'] = [
            '#type' => 'url',
            '#default_value' => $this->configuration['url'],
            '#title' => $this->t('Url'),
            '#description' => $this->t('The URL to the import resource'),
            '#required' => TRUE,
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
        $this->configuration['url'] = $form_state->getValue('url');
    }
}
