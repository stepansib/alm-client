<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 22:23
 */

require 'config.php';
require 'menu.php';

use StepanSib\AlmClient\AlmClient;
use StepanSib\AlmClient\AlmEntityManager;

$almClient = new AlmClient($connectionParams);
$attachmentManager = $almClient->getManager()->getAttachmentManager();

$attachments = $attachmentManager->getAttachments(4010, AlmEntityManager::ENTITY_TYPE_DEFECT);
dump($attachments);

foreach ($attachments as $attachment) {

    $result = $attachmentManager->downloadAttachment(
        __DIR__ . '/../',
        $attachment
    );

}
