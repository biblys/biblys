<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$mm = new MailingManager();

if ($request->getMethod() === "POST") {
    $action = $request->request->get("action");
    $subscriberIds = $request->request->get("subscriberIds");
    $subscriberIds = json_decode($subscriberIds);
    $count = 0;
    foreach ($subscriberIds as $subscriberId) {
        $subscriber = $mm->getById($subscriberId);
        if ($subscriber) {
            if ($action == "resub") {
                $subscriber->set("mailing_checked", 1);
                $subscriber->set("mailing_block", 0);
            } elseif ($action == "unsub") {
                $subscriber->set("mailing_checked", 1);
                $subscriber->set("mailing_block", 1);
            }
            $mm->update($subscriber);
            $count++;
        }
    }
}

$response = new JsonResponse(["count" => $count]);
$response->send();
