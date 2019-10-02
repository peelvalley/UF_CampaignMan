<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Interop\Container\ContainerInterface;
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
}