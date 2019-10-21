<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class OutlookController extends Controller
{
    public function mail()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
                        ->setReturnType(Model\User::class)
                        ->execute();

        echo 'User: '.$user->getDisplayName().'<br/>';
        $messageQueryParams = array (
            // Only return Subject, ReceivedDateTime, and From fields
            "\$select" => "subject,receivedDateTime,from",
            // Sort by ReceivedDateTime, newest first
            "\$orderby" => "receivedDateTime DESC",
            // Return at most 10 results
            "\$top" => "10"
          );
        
          $getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
          $messages = $graph->createRequest('GET', $getMessagesUrl)
                            ->setReturnType(Model\Message::class)
                            ->execute();
        
          foreach($messages as $msg) {
            echo 'Message: '.$msg->getSubject().'<br/>';
          }
    }

    public function root()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
                        ->setReturnType(Model\User::class)
                        ->execute();

        echo 'User: '.$user->getDisplayName().'<br/>';
        $messageQueryParams = array (
            // Only return Subject, ReceivedDateTime, and From fields
            "\$select" => "subject,receivedDateTime,from",
            // Sort by ReceivedDateTime, newest first
            "\$orderby" => "receivedDateTime DESC",
            // Return at most 10 results
            "\$top" => "10"
          );
        
          //$getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
          
          $getMessagesUrl = '/me/drive/root';
          $messages = $graph->createRequest('GET', $getMessagesUrl)
                            ->setReturnType(Model\Drive::class)
                            ->execute();
        
          return $messages;
    }

    public function children()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
                        ->setReturnType(Model\User::class)
                        ->execute();

        echo 'User: '.$user->getDisplayName().'<br/>';
        $messageQueryParams = array (
            // Only return Subject, ReceivedDateTime, and From fields
            "\$select" => "subject,receivedDateTime,from",
            // Sort by ReceivedDateTime, newest first
            "\$orderby" => "receivedDateTime DESC",
            // Return at most 10 results
            "\$top" => "10"
          );
        
          //$getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
          
          $getMessagesUrl = '/me/drive/root/children';
          $messages = $graph->createCollectionRequest("GET", $getMessagesUrl)
                        ->setReturnType(Model\DriveItem::class)
                        ->setPageSize(2);
        

           $docs = $messages->getPage();
           $docArray = [];
           foreach ($docs as $doc){
            $docArray[] = $doc->getName();
            }   
            // foreach ($docs as $doc){
            //     $docArray[] = $doc->getName();
            // }
          return $docArray;
    }

    public function items()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
                        ->setReturnType(Model\User::class)
                        ->execute();

        echo 'User: '.$user->getDisplayName().'<br/>';
        $messageQueryParams = array (
            // Only return Subject, ReceivedDateTime, and From fields
            "\$select" => "subject,receivedDateTime,from",
            // Sort by ReceivedDateTime, newest first
            "\$orderby" => "receivedDateTime DESC",
            // Return at most 10 results
            "\$top" => "10"
          );
        
          //$getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
          
          $getMessagesUrl = '/me/drive/items/'.'01LKUVJTN6Y2GOVW7725BZO354PWSELRRZ/children';
          $messages = $graph->createRequest('GET', $getMessagesUrl)
                            //->setReturnType(Model\DriveItem::class)
                            ->execute();
        
          return $messages;
    }
}
