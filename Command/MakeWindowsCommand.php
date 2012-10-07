<?php

namespace HarvestCloud\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HarvestCloud\CoreBundle\Entity\WindowMaker;
use HarvestCloud\CoreBundle\Entity\SellerHubPickupWindow;

/**
 * generate SellerHubPickupWindows
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-03
 */
class MakeWindowsCommand extends ContainerAwareCommand
{
    /**
     * configure()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     */
    protected function configure()
    {
        $this
            ->setName('harvestcloud:core:make-windows')
            ->setDescription('Make future SellerHubPickupWindows using WindowMakers')
        ;
    }

    /**
     * execute()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $sellerHubRefs = $this->getSellerHubRefs();

        foreach ($sellerHubRefs as $sellerHubRef)
        {
            foreach ($sellerHubRef->getWindowMakers() as $windowMaker)
            {
                $output->writeln('');
                $output->writeln('WindowMaker: # '.$windowMaker->getId());
                $output->writeln('Seller:        '
                  .$windowMaker->getSeller()->getName().' (#'
                  .$windowMaker->getSeller()->getId().')'
                );
                $output->writeln('Hub:           '
                  .$windowMaker->getHub()->getName().' (#'
                  .$windowMaker->getHub()->getId().')'
                );
                $output->writeln('Days of Week:  '.$windowMaker->getDaysOfWeekAsString());
                $output->writeln('Time:          '.$windowMaker->getStartTime().'-'.$windowMaker->getEndTime());

                $output->writeln('Windows:');

                foreach ($windowMaker->getDateAdjustedStartTimes(new \DateTime(), 10) as $start_time)
                {
                    $startTime = new \DateTime($start_time);

                    if ($sellerHubRef->hasWindowAtThisTime($start_time))
                    {
                        $output->writeln('  Exists:      '.$startTime->format('D').' '.$start_time);
                    }
                    else
                    {
                        $output->writeln('  Make:        '.$startTime->format('D').' '.$start_time);

                        $endTime  = clone $startTime;
                        $endTime->add(\DateInterval::createFromDateString('+2 hour'))->format('Y-m-d H:i:s');

                        // Create a new window
                        $window = new SellerHubPickupWindow();
                        $window->setStartTime($startTime);
                        $window->setEndTime($endTime);

                        $sellerHubRef->addSellerHubPickupWindow($window);
                    }
                }

                // First we need to check that the window has not already been
                // created, perhaps by another WindowMaker
                // @todo

            }

            $em->persist($sellerHubRef);
        }

        $em->flush();
    }

    /**
     * getSellerHubRefs()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     *
     * @return array
     */
    protected function getSellerHubRefs()
    {
        $repository = $this->getContainer()->get('doctrine')
            ->getRepository('HarvestCloudCoreBundle:SellerHubRef');

        return $repository->findAll();
    }
}
