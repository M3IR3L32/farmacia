<?php


namespace Acme\DemoBundle\Listener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class NotificationListener implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'requestRefund' => 'onRequestRefund'
        );
    }
    /**
     * @param GenericEvent $event
     */
    public function onRequestRefund(GenericEvent $event)
    {
        $refund = $this->getRefundFromEvent($event);
        $user = $this->getUserFromEvent($event);

        $this->notificateToAdmin(
            'RefundMoneyDeveloper',
            array(
                'refund' => $refund,
                'user' => $user
            )
        );
    }

    /**
     * @param $template
     * @param $messageDatas
     */
    private function notificateToAdmin($template, $messageDatas)
    {
        try {
            $this->notificator->notificateToAdmin(new AdminNotification(
                $template,
                $messageDatas
            ));

        } catch (\Exception $e) {

        }
    }

    /**
     * @param GenericEvent $event
     *
     * @return mixed
     */
    private function getRefundFromEvent(GenericEvent $event)
    {
        return $event->getSubject()->getData('refund');
    }

    /**
     * @param GenericEvent $event
     *
     * @return mixed
     */
    private function getUserFromEvent(GenericEvent $event)
    {
        return $event->getSubject()->getData('user');
    }

}