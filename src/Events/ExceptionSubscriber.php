<?php
declare(strict_types=1);


namespace App\Events;

use App\Factory\JsonResponseInterface;
use App\Normalizer\NormalizerInterface;
use App\Services\ExceptionNormalizerFormatterInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private static array $normalizers;

    public function __construct(
        private SerializerInterface                   $serializer,
        private ExceptionNormalizerFormatterInterface $exceptionNormalizerFormatter,
        private JsonResponseInterface                 $jsonResponse,

    )
    {
    }

    #[ArrayShape([KernelEvents::EXCEPTION => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['processException', 0]
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $result = null;
        /** @var Exception $exception */
        $exception = $event->getThrowable();

        /** @var  NormalizerInterface $normalizer */
        foreach (self::$normalizers as $normalizer) {
            if ($normalizer->supports($exception)) {
                $result = $normalizer->normalize($exception);
                break;
            }
        }

        if (null === $result) {
            $result = $this->exceptionNormalizerFormatter->format(
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $event->setResponse($this->jsonResponse->getJsonResponse(
            $result['code'],
            $this->serializer->serialize($result, 'json')
        ));
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        self::$normalizers[] = $normalizer;
    }
}