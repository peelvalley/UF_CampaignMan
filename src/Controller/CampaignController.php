<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Sprinkle\CampaignMan\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Admin\Controller\GroupController;
use UserFrosting\Support\Exception\ForbiddenException;


class CampaignController extends GroupController
{
    public function pageInfo(Request $request, Response $response, $args)
    {
        $campaign = $this->getCampaignFromParams($args);

        // If the campaign no longer exists, forward to dashboard page
        if (!$campaign) {
            throw new NotFoundException($request, $response);
        }
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'view_campaign', [
                'group' => $campaign->group
            ])) {
            throw new ForbiddenException();
        }
        return $this->ci->view->render($response, 'CampaignMan/pages/campaign.html.twig', [
            'campaign' => $campaign
        ]);
    }


    public function getSubscriptions(Request $request, Response $response, $args)
    {
        $campaign = $this->getCampaignFromParams($args);

        // If the group no longer exists, forward to dashboard page
        if (!$campaign) {
            return $response->withStatus(404);
        }

        // GET parameters
        $params = $request->getQueryParams();
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
         if (!$authorizer->checkAccess($currentUser, 'view_campaign_subscriptions', [
            'group' => $group
        ])) {
        throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;
        $sprunje = $classMapper->createInstance('campaign_subs_sprunje', $classMapper, $params);
        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).

        $sprunje->setQuery($campaign->subscriptions()->with(['subscription','subscriber']));
        return $sprunje->toResponse($response);
    }

    protected function getCampaignFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/campaign/get-by-id.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        // Validate, and throw exception on validation errors.
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            // TODO: encapsulate the communication of error messages from ServerSideValidator to the BadRequestException
            $e = new BadRequestException();
            foreach ($validator->errors() as $idx => $field) {
                foreach($field as $eidx => $error) {
                    $e->addUserMessage($error);
                }
            }
            throw $e;
        }

        $classMapper = $this->ci->classMapper;

        // Get the specified campaign record
        $campaign =  $classMapper->getClassMapping('campaign')::find($data['campaign_id']);
        return $campaign;
    }

}