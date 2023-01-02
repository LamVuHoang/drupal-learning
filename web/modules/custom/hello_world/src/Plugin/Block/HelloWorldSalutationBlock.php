<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Hello World Salutation block.
 *
 * @Block(
 *      id = "hello_world_salutation_block",
 *      admin_label = @Translation("Hello world salutation"),
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements
    ContainerFactoryPluginInterface
{
    /**
     * The salutation service.
     *
     * @var \Drupal\hello_world\HelloWorldSalutation
     */
    protected $salutation;
    /**
     * Constructs a HelloWorldSalutationBlock.
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        HelloWorldSalutation $salutation
    ) {
        parent::__construct(
            $configuration,
            $plugin_id,
            $plugin_definition
        );
        $this->salutation = $salutation;
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
            $container->get('hello_world.salutation')
        );
    }
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $build = [];
        $build[] = [
            '#theme' => 'container',
            '#children' => [
                '#markup' => $this->salutation->getSalutation(),
            ]
        ];
        $url = Url::fromRoute('hello_world.hide_block');
        $url->setOption('attributes', ['class' => 'use-ajax']);
        $build[] = [
            '#type' => 'link',
            '#url' => $url,
            '#title' => $this->t('Remove'),
        ];
        return $build;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [
            'enabled' => 1,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm(
        $form,
        FormStateInterface $form_state
    ) {
        $config = $this->getConfiguration();
        $form['enabled'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Enabled'),
            '#description' => $this->t('Check this box if you want to enable this feature.'),
            '#default_value' => $config['enabled'],
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit(
        $form,
        FormStateInterface $form_state
    ) {
        $this->configuration['enabled'] = $form_state->getValue('enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function blockValidate(
        $form,
        FormStateInterface $form_state
    ) {
        $salutation = $form_state->getValue('salutation');
        if (strlen($salutation) > 20) {
            $form_state->setErrorByName('salutation', $this->t('This salutation is too long (Plugin)'));
        }
    }
}
