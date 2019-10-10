<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Account\Database\Models\Group;
use UserFrosting\Sprinkle\Admin\Controller\GroupController;
use UserFrosting\Support\Exception\ForbiddenException;


class GroupMailingController extends GroupController
{
    public function pageMailingLists(Request $request, Response $response, $args)
    {
        $group = $this->getGroupFromParams($args);

        // If the group no longer exists, forward to dashboard page
        if (!$group) {
            $redirectPage = $this->ci->router->pathFor('uri_dashboard');
            return $response->withRedirect($redirectPage, 404);
        }

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'view_mailing_lists', [
                'group' => $group
            ])) {
            throw new ForbiddenException();
        }
        return $this->ci->view->render($response, 'CampaignMan/pages/group-mailing-lists.html.twig', [
            'group' => $group
        ]);
    }


    public function getMailingLists(Request $request, Response $response, $args)
    {
        $group = $this->getGroupFromParams($args);

        // If the group no longer exists, forward to dashboard page
        if (!$group) {
            return $response->withStatus(404);
        }

        // GET parameters
        $params = $request->getQueryParams();
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
         if (!$authorizer->checkAccess($currentUser, 'view_mailing_lists', [
            'group' => $group
        ])) {
        throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;
        $sprunje = $classMapper->createInstance('mailing_list_sprunje', $classMapper, $params);
        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        $sprunje->setQuery($group->mailingLists());
        return $sprunje->toResponse($response);
    }

    public function getModalCreateSubscription(Request $request, Response $response, $args)
    {
        $group = $this->getGroupFromParams($args);

        // If the group no longer exists, return 404
        if (!$group) {
            return $response->withStatus(404);
        }

        $mailingList = $this->getMailingListFromParams($group, $args);

         // If the mailing list no longer exists, return 404
        if (!$mailingList) {
            return $response->withStatus(404);
        }

        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'create_subscription', [
            'group' => $group
        ])) {
        throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        return $this->ci->view->render($response, 'CampaignMan/modals/subscription.html.twig', [
            'groups' => $classMapper->staticMethod('group', 'where', 'slug', '!=', 'default')->get(),
            'form' => [
                'method' => 'POST',
                'action' => "api/g/{$group->slug}/mailing_lists/ml/{$mailingList->slug}"
            ]
        ]);
    }

    protected function getMailingListFromParams($group, $params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/mailing-list/get-by-slug.yaml');

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

        // Get the specified event record
        $mailingList = $group->mailingLists()->where('slug', $data['ml_slug'])->first();
        return $mailingList;
    }

}