<?php

namespace KuznetsovZfort\NovaFreshdeskButtons;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Cache;
use KuznetsovZfort\Freshdesk\Facades\Freshdesk;
use Laravel\Nova\Http\Requests\NovaRequest;

class Controller extends RoutingController
{
    public function handle(NovaRequest $request)
    {
        $resource = $request->findModelQuery()->firstOrFail();
        $freshdesk = [
            'newUrl' => '',
            'viewUrl' => '',
        ];

        if ($resource instanceof Authenticatable) {
            $cacheKey = "nova-freshdesk-buttons.user.{$resource->id}";
            $cacheDuration = config('nova-freshdesk-buttons.cache.duration');

            $freshdesk = Cache::remember($cacheKey, $cacheDuration, function () use ($resource) {
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

        return response()->json([
            'newUrl' => $freshdesk['newUrl'],
            'viewUrl' => $freshdesk['viewUrl'],
        ]);
    }
}
