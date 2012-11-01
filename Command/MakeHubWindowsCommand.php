<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HarvestCloud\CoreBundle\Entity\HubWindowMaker;

/**
 * generate Hub*Windows
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-30
 */
class MakeHubWindowsCommand extends ContainerAwareCommand
{
    /**
     * configure()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-30
     */
    protected function configure()
    {
        $this
            ->setName('harvestcloud:core:make-hub-windows')
            ->setDescription('Make future Hub*Windows using WindowMakers')
            ->addOption('num-days', null, InputOption::VALUE_OPTIONAL, 'Number of days in future to create windows for', 21)
            ->addOption('num-window-makers', null, InputOption::VALUE_OPTIONAL, 'Number of WindowMakers to run during this job', 10)
        ;
    }

    /**
     * execute()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-30
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $windowMakers = $this->getContainer()->get('doctrine')
            ->getRepository('HarvestCloudCoreBundle:HubWindowMaker')
            ->findForWindowMakerCommand($input->getOption('num-window-makers'));

        $output->writeln('');
        $output->writeln('Start job at:           '.date('Y-m-d H:i:s'));
        $output->writeln('Number of days:         '.$input->getOption('num-days'));
        $output->writeln('Number of WindowMakers: '.$input->getOption('num-window-makers'));

        foreach ($windowMakers as $windowMaker)
        {
            $output->writeln('');
            $output->writeln('  WindowMaker: #        '.$windowMaker->getId());
            $output->writeln('  Hub:                  '
              .$windowMaker->getHub()->getName().' (#'
              .$windowMaker->getHub()->getId().')'
            );
            $output->writeln('  Days of Week:         '.$windowMaker->getDaysOfWeekAsString());
            $output->writeln('  Time:                 '.$windowMaker->getStartTime().'-'.$windowMaker->getEndTime());

            $windows = $windowMaker->makeWindows($input->getOption('num-days'));

            $week_num = date('W');

            if (count($windows))
            {
                $output->writeln('  '.count($windows).' new windows:');
                $output->writeln('');

                foreach ($windows as $window)
                {
                    // Add an extra line between weeks for legibility, but not
                    // if the first week is next week
                    if ($window->getStartTime()->format('W') != $week_num && $week_num != date('W'))
                    {
                        $output->writeln('');
                    }

                    $output->writeln('    <info>'.$window->getStartTime()->format('H:i D d M Y').'</info>');

                    $week_num = $window->getStartTime()->format('W');
                }
            }
            else
            {
              $output->writeln('  No new windows made');
            }

            $em->persist($windowMaker);
            $em->flush();
        }

        $output->writeln('');
        $output->writeln('End job at              '.date('Y-m-d H:i:s'));
        $output->writeln('');
    }
}
