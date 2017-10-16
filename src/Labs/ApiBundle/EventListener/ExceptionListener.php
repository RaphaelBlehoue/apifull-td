<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 15/10/2017
 * Time: 11:30
 */

namespace Labs\ApiBundle\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function onKernelException (GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $message = ExceptionFormatNormalizer::ExceptionFormat;
        $response = New Response();

        if ( $exception instanceof HttpExceptionInterface) {
            $class = get_class($exception);
            foreach ($message as $key => $value)
            {
                if ($class == $key ) {
                    $line = [
                        'header' => $exception->getHeaders(),
                        'MessageInterne'   => $exception->getMessage()
                    ];
                    $push_array = array_merge($value['errorFormat'], $line);
                    $dataError = $this->serializer->serialize($push_array, 'json');
                    $this->logger->info('Error exception', $push_array);
                    $response->setContent($dataError);
                    $response->setStatusCode($exception->getStatusCode());
                    $response->headers->replace($exception->getHeaders());
                }
            }
        }else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}