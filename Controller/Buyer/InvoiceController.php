<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use HarvestCloud\CoreBundle\Entity\Invoice\Invoice;
use HarvestCloud\CoreBundle\Entity\Payment\InvoicePayment;
use HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * InvoiceController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-23
 */
class InvoiceController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-23
     */
    public function indexAction()
    {
        $buyer = $this->get('security.context')
            ->getToken()
            ->getUser()
            ->getCurrentProfile()
        ;

        $invoices = $this->get('doctrine')
            ->getRepository('\HarvestCloud\CoreBundle\Entity\Invoice\Invoice')
            ->findByCustomer($buyer)
        ;

        return $this->render('HarvestCloudCoreBundle:Buyer/Invoice:index.html.twig', array(
          'invoices' => $invoices,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-23
     *
     * @Route("/invoice/{id}")
     * @ParamConverter("invoice", class="\HarvestCloud\CoreBundle\Entity\Invoice")
     *
     * @param  Invoice  $invoice
     */
    public function showAction(Invoice $invoice, Request $request)
    {
        $buyer = $this->get('security.context')
            ->getToken()
            ->getUser()
            ->getCurrentProfile()
        ;

        // $savedCreditCards = $buyer->getSavedCreditCards();

        return $this->render('HarvestCloudCoreBundle:Buyer/Invoice:show.html.twig', array(
            'invoice' => $invoice,
        ));
    }

    /**
     * payWithStripe
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @Route("/invoice/pay/stripe/{id}")
     * @ParamConverter("invoice", class="\HarvestCloud\CoreBundle\Entity\Invoice")
     *
     * @param  Invoice  $invoice
     */
    public function payWithStripeAction(Invoice $invoice, Request $request)
    {
        $buyer = $this->get('security.context')
            ->getToken()
            ->getUser()
            ->getCurrentProfile()
        ;

        if ($buyer !== $invoice->getCustomer()) {
            throw new NotFoundHttpException('Buyer is not Invoice Customer');
        }

        // First we need to create a payment
        $payment = new InvoicePayment(array($invoice));

        // Save the Payment, so that we get an id
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($payment);
        $em->flush();

        $this->get('payment.stripe');

        // Grap Stripe token
        $token  = $_POST['stripeToken'];

        try {
            // First create a Stripe customer
            $customer = \Stripe_Customer::create(array(
              'card'        => $token,
              'description' => '#'.$invoice->getCustomer()->getId().' '.$invoice->getCustomer()->getName(),
            ));

            $charge = \Stripe_Charge::create(array(
                'customer'    => $customer->id,
                'amount'      => floor($payment->getAmount()*100),
                'currency'    => 'usd',
                'description' => 'Invoice Payment #'.$payment->getId(),
            )); //, $invoice->getVendor()->getStripeAccessToken());

            // Create SavedCreditCard
            $creditCard = new \HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard();
            $creditCard->setPaymentServiceKey('stripe');
            $creditCard->setPaymentServiceToken($customer->id);
            $creditCard->setType($charge['card']['type']);
            $creditCard->setExpiryDateFromYearAndMonth($charge['card']['exp_year'], $charge['card']['exp_month']);
            $creditCard->setLastFourDigits($charge['card']['last4']);
            $creditCard->setNameOnCard($charge['card']['name']);

            $invoice->getCustomer()->addSavedCreditCard($creditCard);

            $em->persist($invoice->getCustomer());
            $em->flush();


            // Everything went OK, so let's post the Payment
            $payment->post();

            // Create a Customer
        } catch (\Stripe_CardError $e) {
            // @todo Send some messages back to the browser
            // for now, we'll just re-throw the exception
            throw $e;
        }

        // Save the Payment again after the post()
        $em->persist($payment);
        $em->flush();

        return $this->redirect($this->generateurl('Buyer_invoice_show', array(
            'id' => $invoice->getId(),
        )));
    }

    /**
     * payWithSavedCreditCard
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-03
     *
     * @Route("/invoice/{id}/pay/saved-credi-card/{saved_credit_card_id}")
     * @ParamConverter("creditCard", class="\HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard",
     *        options={"id" = "saved_credit_card_id"})
     *
     * @param  Invoice  $invoice
     */
    public function payWithSavedCreditCardAction(Invoice $invoice, SavedCreditCard $creditCard)
    {
        $buyer = $this->get('security.context')
            ->getToken()
            ->getUser()
            ->getCurrentProfile()
        ;

        if ($buyer !== $invoice->getCustomer()) {
            throw new NotFoundHttpException('Buyer is not Invoice Customer');
        }

        // First we need to create a payment
        $payment = new InvoicePayment(array($invoice));

        // Save the Payment, so that we get an id
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($payment);
        $em->flush();

        $this->get('payment.stripe');

        try {
            // First create a Stripe customer
            $customer = \Stripe_Customer::retrieve($creditCard->getPaymentServiceToken());

            $charge = \Stripe_Charge::create(array(
                'customer'    => $customer->id,
                'amount'      => floor($payment->getAmount()*100),
                'currency'    => 'usd',
                'description' => 'Invoice Payment #'.$payment->getId(),
            )); //, $invoice->getVendor()->getStripeAccessToken());

            // Everything went OK, so let's post the Payment
            $payment->post();

            // Create a Customer
        } catch (\Stripe_CardError $e) {
            // @todo Send some messages back to the browser
            // for now, we'll just re-throw the exception
            throw $e;
        }

        // Save the Payment again after the post()
        $em->persist($payment);
        $em->flush();

        return $this->redirect($this->generateurl('Buyer_invoice_show', array(
            'id' => $invoice->getId(),
        )));
    }
}
