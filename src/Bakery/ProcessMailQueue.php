<?php


namespace UserFrosting\Sprinkle\CampaignMan\Bakery;

use Carbon\Carbon;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UserFrosting\Sprinkle\Core\Mail\EmailRecipient;
use UserFrosting\Sprinkle\Core\Mail\TwigMailMessage;
use UserFrosting\System\Bakery\BaseCommand;
use Spipu\Html2Pdf\Html2Pdf;


class ProcessMailQueue extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('process-mail-queue')
             ->setDescription('Process outgoing email queue')
             ->setHelp('This command proccesses the outgoing email queue, sending all pending emails.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title('Processing Email Queue');

        /** @var \UserFrosting\Support\Repository\Repository */
        $config = $this->ci->config;

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $phpMailer = $this->ci->mailer->getPhpMailer();

        $queueCount = $classMapper->staticMethod('mailing_queue','count');

        while ($mailItem = $classMapper->getClassMapping('mailing_queue')::whereNull('metadata->status')->orWhere('metadata->status', '!=', 'error')->first()) {
            $remaining = $classMapper->staticMethod('mailing_queue','count');
            $completed = $queueCount - $remaining +1;
            $this->io->writeln("Sending item {$completed} of {$queueCount}");

            try {
                // Create and send email
                $message = new TwigMailMessage($this->ci->view, $mailItem->template);
                $message->from($mailItem->from ? [
                    'email' => $mailItem->from['email'],
                    'name' => $mailItem->from['name']

                ] : $config['address_book.admin'])
                        ->addEmailRecipient(new EmailRecipient(...$mailItem->to))
                        ->addParams(
                            array_merge($mailItem->data, ... array_map(function ($paramInfo) use ($classMapper) {
                                    return [
                                        $paramInfo['paramName'] => call_user_func_array(
                                            array(
                                                $classMapper,
                                                $paramInfo['function']),
                                            $paramInfo['functionParams']
                                            )
                                    ];
                                }, $mailItem->data['params']) ?? []
                            )
                        );

                foreach ($mailItem->attachments as $attachment) {
                    if ($attachment['type'] == 'pdf') {
                        $pdf = $this->generatePDF($attachment['template'],
                            array_merge($attachment['data'] ?? [], ... array_map(function ($paramInfo) use ($classMapper) {
                                return [
                                    $paramInfo['paramName'] => call_user_func_array(
                                        array(
                                            $classMapper,
                                            $paramInfo['function']),
                                        $paramInfo['functionParams']
                                        )
                                    ];
                                }, $attachment['params']) ?? []
                            )
                        );
                        $phpMailer->addStringAttachment($pdf->output(NULL, 'S'), $attachment['filename']);
                    } else {
                        throw new Exception("{$attachment['type']} not implemented");
                    }
                }

                $this->ci->mailer->send($message);
                $mailItem->delete();
                $this->io->success("Email sent");

            } catch (Exception $e) {
                $this->io->error("Unable to send email: {$e->getMessage()}");
                $mailItem->update(['metadata->status' => 'error']);
                $phpMailer->clearAllRecipients();
            }
            $phpMailer->clearAttachments();
        }
    }
    private function generatePDF($template, $params=[])
    {
        $pdf = new Html2Pdf('P', 'A4', 'en');

        $contents = $this->ci->view->fetch($template, $params);

        $pdf->writeHTML($contents);

        return $pdf;
    }
}

