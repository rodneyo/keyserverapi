<?php
namespace Roles;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\View\Http\ExceptionStrategy;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

/**
 * JsonExceptionStrategy 
 * Override the existing Exception Strategy by attaching a listener to the even manager
 * to listen for dispatch errors at a higer priority than the default listener.
 * 
 * @uses ExceptionStrategy
 * @author StoneMor
 */
class JsonExceptionStrategy extends ExceptionStrategy
{

    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'prepareExceptionViewModel'), 100);
    }

    /**
     * Create an exception json view model, and set the HTTP status code
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function prepareExceptionViewModel(MvcEvent $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error)) {
            return;
        }

        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }

        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
            case Application::ERROR_ROUTER_NO_MATCH:
                $modelData['exception'] = 'API Error';
                $e->setResult(new JsonModel($modelData));
                $e->setError(true);
                break;

            case Application::ERROR_EXCEPTION:
                $exception = $e->getParam('exception');
                print_r($exception->getMessage()); exit;
                break;
            default:
                $exception = $e->getParam('exception');
                $modelData = array(
                    'message' => $exception->getMessage(),
                    'exception' => 'API Error'
                  );

                if ($this->displayExceptions()){
                    $modelData['exception'] = $exception;
                }
                $e->setResult(new JsonModel($modelData));
                $e->setError(true);

                $response = $e->getResponse();
                if (!$response) {
                    $response = new HttpResponse();
                    $e->setResponse($response);
                }
                $response->setStatusCode(500);
                break;
        }
    }
}
