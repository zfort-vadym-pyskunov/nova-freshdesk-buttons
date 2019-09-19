<?php

namespace KuznetsovZfort\NovaFreshdeskButtons;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use KuznetsovZfort\Freshdesk\Facades\Freshdesk;
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
        parent::__construct('Freshdesk', null, null);

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
        $styles[] = $this->config("styles.{$this->style}");

        $freshdesk = [
            'newUrl' => '',
            'viewUrl' => '',
        ];

        if ($resource instanceof Authenticatable) {
            $cacheKey = "nova-freshdesk-buttons.user.{$resource->id}";

            $freshdesk = Cache::remember($cacheKey, $this->config('cache.duration'), function () use ($resource) {
                $contact = Freshdesk::getContact($resource->email);
                if (empty($contact)) {
                    return [
                        'newUrl' => Freshdesk::getNewTicketUrl('email', $resource->email),
                        'viewUrl' => '',
                    ];
                }

                return [
                    'newUrl' => Freshdesk::getNewTicketUrl('contactId', $contact->id),
                    'viewUrl' => Freshdesk::getContactTicketsUrl($resource),
                ];
            });
        }

        $this->withMeta([
            'indexName' => '',
            'freshdeskNewUrl' => $freshdesk['newUrl'],
            'freshdeskViewUrl' => $freshdesk['viewUrl'],
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

    /**
     * @param string $key
     *
     * @return mixed
     */
    private function config(string $key)
    {
        return Arr::get(config('nova-freshdesk-buttons'), $key);
    }
}
