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


    public function getModalCreate(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        return $this->ci->view->render($response, 'modals/subscriber.html.twig', [
            'groups' => $classMapper->staticMethod('group', 'where', 'slug', '!=', 'default')->get(),
            'form' => [
                'method' => 'POST',
                'action' => 'api/subscribers',
            ],
            'editable' => TRUE
        ]);
    }

    // public function getModalEdit(Request $request, Response $response, $args)
    // {
    //     // GET parameters
    //     $params = $request->getQueryParams(); // TODO load existing subscriber for edit

    //     /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
    //     $authorizer = $this->ci->authorizer;
    //     /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
    //     $currentUser = $this->ci->currentUser;

    //     /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
    //     $classMapper = $this->ci->classMapper;

    //     return $this->ci->view->render($response, 'modals/add-subscriber.html.twig', [
    //         'groups' => $classMapper->staticMethod('group', 'get'),
    //         'form' => [
    //             'method' => 'PUT',
    //             'action' => "api/subscribers/s/{$subscriber->id}",
    //         ],
    //         'editable' => TRUE  //TODO add access control
    //     ]);
    // }




}

