<?php
namespace CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use CoreBundle\Entity\Ipn;
use SQLite3;
use DateTime;
use DateTimeZone;

class LoaddbCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('loaddb')
            ->setDescription('load the database from old one')
            ->addArgument(
                'db',
                InputArgument::REQUIRED,
                'Which database do you want to load?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $utc = new DateTimeZone('UTC');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $db = $db = new SQLite3($input->getArgument('db'));
        $lastDate = $em->getRepository('CoreBundle:Ipn')->getLastDate();
        if ($lastDate){
            $stmt = $db->prepare('SELECT * FROM ipn WHERE ipn.ts > :lastdate');
            $stmt->bindValue('lastdate',$lastDate,SQLITE3_TEXT);
            $result = $stmt->execute();
        } else{
            $result = $db->query('SELECT * FROM ipn');
        }
        $output->writeln("Data will be loaded : ");
        $cpt = 0;
        while($result->fetchArray()){
            $cpt++;
        }
        $progress = new ProgressBar($output, $cpt);
        $result->reset();
        $progress->start();
        while ($ipnOne = $result->fetchArray()) {
            $ipn = new Ipn();
            $ipn->setStatus("NEW");
            $ipn->setTs(new DateTime($ipnOne['ts']));
            $ipn->setVadsSiteId($ipnOne['vads_site_id']);
            $ipn->setVadsUrlCheckSrc($ipnOne['vads_url_check_src']);
            $ipn->setVadsPaymentSrc($ipnOne['vads_payment_src']);
            $ipn->setVadsShopName($ipnOne['vads_shop_name']);
            $ipn->setVadsCtxMode($ipnOne['vads_ctx_mode']);
            $ipn->setVadsTransUuid($ipnOne['vads_trans_uuid']);
            $ipn->setVadsTransId($ipnOne['vads_trans_id']);
            $ipn->setVadsOrderId($ipnOne['vads_order_id']);
            $ipn->setVadsOrderInfo($ipnOne['vads_order_info']);
            $ipn->setVadsPaymentConfig($ipnOne['vads_payment_config']);
            $date = DateTime::createFromFormat('YmdHis',$ipnOne['vads_effective_creation_date'],$utc);
            $ipn->setVadsEffectiveCreationDate($date instanceof DateTime ? $date : null);
            $output->writeln($ipnOne['vads_sub_effect_date']);
            $ipn->setVadsOperationType($ipnOne['vads_operation_type']);
            $ipn->setVadsTransStatus($ipnOne['vads_trans_status']);
            $ipn->setVadsResult($ipnOne['vads_result']);
            $ipn->setVadsExtraResult($ipnOne['vads_extra_result']);
            $ipn->setVadsEffectiveAmount($ipnOne['vads_effective_amount']);
            $ipn->setVadsCurrency($ipnOne['vads_currency']);
            $ipn->setVadsContractUsed($ipnOne['vads_contract_used']);
            $ipn->setVadsAuthMode($ipnOne['vads_auth_mode']);
            $ipn->setVadsCardBrand($ipnOne['vads_card_brand']);
            $ipn->setVadsCardNumber($ipnOne['vads_card_number']);
            $ipn->setVadsPaymentSeq($ipnOne['vads_payment_seq']);
            $ipn->setVadsCustEmail($ipnOne['vads_cust_email']);
            $ipn->setVadsCaptureDelay($ipnOne['vads_capture_delay']);
            $date = DateTime::createFromFormat('YmdHis',$ipnOne['vads_presentation_date'],$utc);
            $ipn->setVadsPresentationDate($date instanceof DateTime ? $date: null);
            $ipn->setVadsWarrantyResult($ipnOne['vads_warranty_result']);
            $ipn->setVadsRiskControl($ipnOne['vads_risk_control']);
            $ipn->setVadsValidationMode("0");
            $ipn->setVadsRecurrenceStatus($ipnOne['vads_recurrence_status']);
            $ipn->setVadsIdentifierStatus($ipnOne['vads_identifier_status']);
            $ipn->setVadsIdentifier($ipnOne['vads_identifier']);
            $ipn->setVadsSubscription($ipnOne['vads_subscription']);
            $ipn->setVadsSubDesc($ipnOne['vads_sub_desc']);
            $date = DateTime::createFromFormat('YmdHis',$ipnOne['vads_sub_effect_date'],$utc);
            $ipn->setVadsSubEffectDate($date instanceof DateTime ? $date: null);
            $ipn->setVadsSubCurrency($ipnOne['vads_sub_currency']);
            $ipn->setVadsSubAmount($ipnOne['vads_sub_amount']);
            $ipn->setVadsSubInitAmountNumber($ipnOne['vads_sub_init_amount_number']);
            $ipn->setVadsSubInitAmount($ipnOne['vads_sub_init_amount']);
            $ipn->setVadsContrib($ipnOne['vads_contrib']);
            $ipn->setVadsExtInfoDonation($ipnOne['vads_ext_info_donation']);
            $ipn->setVadsExtInfoDonationRecipient($ipnOne['vads_ext_info_donation_recipient']);
            $ipn->setVadsExtInfoDonationRecipientName($ipnOne['vads_ext_info_donation_recipient_name']);
            $ipn->setVadsExtInfoDonationMerchant($ipnOne['vads_ext_info_donation_merchant']);
            $ipn->setSignature($ipnOne['signature']);
            $ipn->setFull($ipnOne['full']);
            $ipn->setChecked($ipnOne['checked']);
            $data = json_decode($ipnOne['full']);
            $ipn->setIdClient($data->vads_cust_id);
            $em->persist($ipn);

            $em->flush();
            $progress-> setOverwrite(true);
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("All data loaded");
    }
}