<?php

namespace MtSimpleRbac\View;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

use MtSimpleRbac\Exception\AccessDeniedException;

class AccessDeniedStrategy implements ListenerAggregateInterface
{

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    public function detectAccessDeniedError(MvcEvent $e)
    {
        $exception = $e->getParam('exception');
        if ($exception instanceof AccessDeniedException) {
            $vars = $e->getResult();
            if ($vars instanceof Response) {
                // Already have a response as the result
                return;
            }

            if (!$vars instanceof ViewModel) {
                $model = new ViewModel();
            } else {
                $model = $vars;
            }
            $model->setTemplate('mt-simple-rbac/access-denied');
            $e->setResult($model);
        }
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'detectAccessDeniedError'));
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}