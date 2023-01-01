<?php

namespace Drupal\hero_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hero_module\HeroArticleService;
use Drupal\Core\Form\FormStateInterface;

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
    protected $_heroArticleService;
    /**
     * Constructs a HelloWorldSalutationBlock.
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        HeroArticleService $heroArticleService
    ) {
        parent::__construct(
            $configuration,
            $plugin_id,
            $plugin_definition
        );
        $this->_heroArticleService = $heroArticleService;
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
            $container->get('hero_module.getSalutation')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // $config = $this->getConfiguration();
        return [
            '#markup' => $this->_heroArticleService->clock(),
        ];
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
    public function blockForm($form, FormStateInterface $form_state)
    {
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
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['enabled'] = $form_state->getValue('enabled');
    }
}
