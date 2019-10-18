<?php

namespace UserFrosting\Sprinkle\CampaignMan\Controller;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\CampaignMan\Fortress\RequestDataTransformer;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Exception\BadRequestException;


class SubscriptionController extends SimpleController
{

    public function updateInfo(Request $request, Response $response, $args)
    {
        // Get the subscription
        $subscription = $this->getSubscriptionFromParams($args);
        if (!$subscription) {
            throw new NotFoundException();
        }
        // Get PUT parameters: (name, slug, icon, description)
        $params = $request->getParsedBody();
        /** @var \UserFrosting\Sprinkle\Core\Alert\AlertStream $ms */
        $ms = $this->ci->alerts;
        // Load the request schema
        $schema = new RequestSchema('schema://requests/subscription/edit-info.yaml');
        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);
        $error = false;
        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        $subscriptionSchema = new RequestSchema('schema://requests/subscription/data.yaml');
        // Whitelist and set parameter defaults
        $subscriptionTransformer = new RequestDataTransformer($subscriptionSchema);
        $subscriptionData = $subscriptionTransformer->transform($params);
        // Validate request data
        $subscriptionValidator = new ServerSideValidator($subscriptionSchema, $this->ci->translator);
        if (!$subscriptionValidator->validate($subscriptionData)) {
            $ms->addValidationErrors($subscriptionValidator);
            $error = true;
        }

        // Determine targeted fields
        $fieldNames = [];
        foreach ($data as $name => $value) {
            $fieldNames[] = $name;
        }

        foreach ($subscriptionData as $name => $value) {
            $fieldNames[] = $name;
        }
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;
        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;
        // Access-controlled resource - check that currentUser has permission to edit submitted fields for this subscription
        if (!$authorizer->checkAccess($currentUser, 'update_subscription_field', [
            'group' => $subscription->mailingList->group,
            'fields' => array_values(array_unique($fieldNames)),
        ])) {
            throw new ForbiddenException();
        }
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        if ($error) {
            return $response->withJson([], 400);
        }
        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction(function () use ($data, $subscriptionData, $subscription, $currentUser) {
            // Update the subscription and generate success messages
            foreach ($data as $name => $value) {
                if ($value != $subscription->$name) {
                    $subscription->$name = $value;
                }
            }

            foreach ($subscriptionData as $name => $value) {
                if ($value != $subscription->$data[$name]) {
                    $subscription->update(["data->$name" => $value]);
                }
            }
            $subscription->save();
            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} updated details for subscription {$subscription->subscriber_name}.", [
                'type'    => 'subscription_update_info',
                'user_id' => $currentUser->id,
            ]);
        });
        $ms->addMessageTranslated('success', 'SUBSCRIPTION.UPDATE', [
            'name' => $subscription->subscriber_name,
        ]);
        return $response->withJson([], 200);
    }

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

        // Load validation rules
        $schema = new RequestSchema('schema://requests/subscription/edit-info.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

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
            'page' => [
                'validators' => $validator->rules('json', false),
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

