<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\CampaignMan\Fortress\RequestDataTransformer;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\ForbiddenException;







class SubscriptionController extends SimpleController
{

    public function getModalEdit(Request $request, Response $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();
        $subscription = $this->getSubscriptionFromParams($params);

        // If the subscription no longer exists, return 404
        if (!$subscription) {
            return $response->withStatus(404);
        }

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        return $this->ci->view->render($response, 'CampaignMan/modals/subscription.html.twig', [
            'groups' => $classMapper->staticMethod('group', 'get'),
            'subscription' => $subscription,
            'form' => [
                'method' => 'PUT',
                'action' => "api/subscriptions/s/{$subscription->id}",
                'fields' => [
                    'disabled' => ['email']
                ]
            ],
            'editable' => TRUE  //TODO add access control
        ]);
    }

    protected function getSubscriptionFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/subscription/get-by-id.yaml');

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

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Get the specified subscription
        $subscription = $classMapper->staticMethod('subscription', 'find', $data['subscription_id']);
        return $subscription;
    }



}

