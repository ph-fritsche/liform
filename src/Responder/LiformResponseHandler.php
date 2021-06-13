<?php
namespace Pitch\Liform\Responder;

use Pitch\AdrBundle\Responder\ResponseHandlerInterface;
use Pitch\AdrBundle\Responder\ResponsePayloadEvent;
use Pitch\Liform\LiformInterface;
use Symfony\Component\Form\FormInterface;

class LiformResponseHandler implements ResponseHandlerInterface
{
    protected ?LiformInterface $liform;

    public function __construct(
        ?LiformInterface $liform = null
    ) {
        $this->liform = $liform;
    }

    public function getSupportedPayloadTypes(): array
    {
        return [
            FormInterface::class,
        ];
    }

    public function handleResponsePayload(ResponsePayloadEvent $payloadEvent)
    {
        /** @var FormInterface */
        $form = $payloadEvent->payload;

        $expectedSubmit = \in_array($payloadEvent->request->getMethod(), ['PATCH', 'POST', 'PUT', 'DELETE']);
        $payloadEvent->httpStatus ??= $expectedSubmit && (!$form->isSubmitted() || !$form->isValid()) ? 400 : 200;

        $view = $form->createView();
        $payloadEvent->payload = (array) $this->liform->transform($view);
    }
}
