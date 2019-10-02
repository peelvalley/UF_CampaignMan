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
        return $this->ci->view->render($response, 'pages/group-mailing-lists.html.twig', [
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
        return $response->withJson($group->debug(), 200)
    }
}