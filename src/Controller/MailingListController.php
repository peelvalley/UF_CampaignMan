<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Sprinkle\Core\Util\EnvironmentInfo;
use UserFrosting\Sprinkle\FormGenerator\Form;
use UserFrosting\Sprinkle\UserProfile\Util\UserProfileHelper;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Repository\Loader\YamlFileLoader;






class MailingListController extends SimpleController
{
    public function getList(Request $request, Response $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;
        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'view_mailing_lists')) {
            throw new ForbiddenException();
        }
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;
        $sprunje = $classMapper->createInstance('mailing_list_sprunje', $classMapper, $params);
        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }

    public function pageInfo(Request $request, Response $response, $args)
    {
        $mailingList = $this->getMailingListFromParams($args);
        // If the user no longer exists, forward to main user listing page
        if (!$mailingList) {
            throw new NotFoundException();
        }
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;
        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'view_mailinglist', [
            'mailing_list' => $mailingList,
            'group' => $mailingList->group
        ])) {
            throw new ForbiddenException();
        }

        // Determine buttons to display
        $editButtons = [
            'hidden' => [],
        ];
        if (!$authorizer->checkAccess($currentUser, 'update_mailing_list_field', [
            'mailing_list' => $mailingList,
            'group' => $mailingList->group,
            'fields' => ['description']
        ])) {
            $editButtons['hidden'][] = 'edit';
        }

        if (!$authorizer->checkAccess($currentUser, 'delete_mailing_list', [
            'mailing_list' => $mailingList,
            'group' => $mailingList->group
        ])) {
            $editButtons['hidden'][] = 'delete';
        }

        $widgets = [
            'hidden' => [],
        ];
        if (!$authorizer->checkAccess($currentUser, 'add_subscriber', [
            'mailing_list' => $mailingList,
            'group' => $mailingList->group
        ])) {
            $widgets['hidden'][] = 'add_subscriber';
        }
        return $this->ci->view->render($response, 'pages/mailing-list.html.twig', [
            'user'            => $user,
            'tools'           => $editButtons,
            'widgets'         => $widgets
        ]);
    }

    public function pageList(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;
        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'view_mailing_lists')) {
            throw new ForbiddenException();
        }
        return $this->ci->view->render($response, 'pages/mailing-lists.html.twig', [

        ]);
    }


    public function getModalAddSubscriber(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        return $this->ci->view->render($response, 'modals/add-subscriber.html.twig', [
            'groups' => $classMapper->staticMethod('group', 'where', 'slug', '!=', 'default')->get(),
            'form' => [
                'method' => 'POST',
                'action' => 'api/mailing_lists/add-subscriber',
            ],
            'editable' => TRUE
        ]);
    }

       protected function getMailingListFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/mailing-list/get-by-id.yaml');

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

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Get the specified event record
        $mailingList = $classMapper->staticMethod('mailing_list', 'find', $data['ml_id']);
        return $mailingList;
    }

}

