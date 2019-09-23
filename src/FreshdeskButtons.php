<?php

namespace KuznetsovZfort\NovaFreshdeskButtons;

use Laravel\Nova\Fields\Field;

class FreshdeskButtons extends Field
{
    /**
     * @var string
     */
    public $component = 'nova-freshdesk-buttons';

    /**
     * @var bool
     */
    public $showOnUpdate = false;

    /**
     * @var bool
     */
    public $showOnCreation = false;

    /**
     * @var bool
     */
    public $showOnDetail = false;

    /**
     * @var string
     */
    private $style = 'default';

    /**
     * @var string
     */
    private $newLabel = '';

    /**
     * @var string
     */
    private $viewLabel = '';

    /**
     * @param string $newLabel
     * @param string $viewLabel
     */
    public function __construct(string $newLabel, string $viewLabel)
    {
        parent::__construct('Freshdesk', 'freshdesk', null);

        $this->newLabel = $newLabel;
        $this->viewLabel = $viewLabel;
    }

    /**
     * @param mixed $resource
     * @param null|string $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        $styles = [];
        $styles[] = 'nova-freshdesk-buttons';
        $styles[] = 'nova-freshdesk-buttons-' . strtolower(class_basename($resource));
        $styles[] = config("nova-freshdesk-buttons.styles.{$this->style}");

        $this->withMeta([
            'indexName' => '',
            'freshdeskStyle' => $styles,
            'freshdeskNewLabel' => $this->newLabel,
            'freshdeskViewLabel' => $this->viewLabel,
        ]);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function style(string $value)
    {
        $this->style = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnForms()
    {
        parent::onlyOnForms();
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnDetail()
    {
        parent::onlyOnDetail();
        $this->showOnDetail = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnForms()
    {
        parent::exceptOnForms();
        $this->showOnDetail = false;

        return $this;
    }
}
