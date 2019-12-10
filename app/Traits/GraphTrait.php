<?php

namespace App\Traits;

use Microsoft\Graph\Graph;

use Microsoft\Graph\Model;

trait GraphTrait 
{
    //

    private $clientId;
    private $contentType = "application/x-www-form-urlencoded";
    private $grantType = "client_credentials";
    private $endpoint ;
    private $scope = "https://graph.microsoft.com/.default";
    private $clientSecret;
    private $_token;
    public $graphClient;


    private $_UserId;
    private $_RootMasterId;
    private $_RepositoryId;
    private $_RootId;



    public function __construct()
    {

        $this->clientId  = env('OAUTH_APP_ID');
        $this->endpoint = "https://login.microsoftonline.com/".env('OAUTH_TENANT_ID').'/oauth2/v2.0/token';
        $this->clientSecret = env('OAUTH_APP_PASSWORD');
        
    }
    public function interfaceRemitoOrien($remito)
    {
        if ($this->graphClient == null) 
        {
            $this->graphClient = new Graph();
            $this->graphClient->setAccessToken($this->getAccessToken());
        }

        $graph = $this->graphClient;

        $this->_UserId = $this->_getUserId($graph);

        $this->_RootMasterId = $this->_getRootMasterId($graph);

        $RepositoryId = $this->_getRepositoryId($graph, $this->_RootMasterId);

        $stream = sprintf("1212121,00;lalalajs;0,44\n");
        $stream .= sprintf("1212121,00;lalalajs;0,44\n");
       

        $numero_remito = str_pad( $remito->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $remito->numero_remito, 8, "0", STR_PAD_LEFT);
        $filename  = 'RT-ORIEN-'.$numero_remito.'.csv';
        
        
        
        $registro = '';
        $separador = ';';
        foreach ($remito->articulos as $key => $art) {
            # code...            
            $registro .=     $numero_remito.
                            $separador. 
                            date_format(date_create($remito->fecha_remito), 'Ymd').
                            $separador.
                            'EMP00'.
                            $separador.
                            'FC|OEP'.
                            $separador.
                            $art->referencia.
                            $separador.
                            $remito->customer->codigo.
                            $separador.
                            '1'.
                            $separador.
                            $art->codigo.
                            $separador.
                            $art->cantidad.PHP_EOL;

            
        }
        

        $fileok = $this->_upload($graph, $this->_RootMasterId, $RepositoryId, $registro, $filename);

       return $fileok;
    }

    public function _getUserId($graph){
       
        $results = $graph->createCollectionRequest('GET', '/users/?$filter=startswith(mail, \''.env('ONEDRIVE_USERNAME').'\')')
        ->setReturnType(Model\User::class)
        ->setPageSize(1);
        

        $users = $results->getPage();



        foreach($users as $user){
            if ($user->getMail() ==  env('ONEDRIVE_USERNAME')){
                $UserId = $user->getId();
                return $UserId;
            }
          

        }

        
        return '';

    }

    public function _upload($graph, $RootMasterId, $RepositoryId, $stream, $filename){

      
        $api = '/drives/'.$RootMasterId.'/items/'.$RepositoryId.':/'.$filename.':/content';
             
      

        $res = $graph->createCollectionRequest('PUT',$api)
                    ->attachBody($stream)
                    ->setReturnType(Model\DriveItem::class)
                    
                    ->execute();

                                    
       
        return $res->getId();


    }

    public function _getRepositoryId($graph, $RootMasterId){
        $pages = $graph->createCollectionRequest('GET', '/drives/'.$RootMasterId.'/root:/'.env('ONEDRIVE_REPOSITORY_NAME').':/')
        ->setReturnType(Model\DriveItem::class)
        ->setPageSize(1);

        $folders = $pages->getPage();

        return $folders->getId();

        

    }
    public function _getRootId($graph, $RootMasterId){
        $pages = $graph->createCollectionRequest('GET', '/drives/'.$RootMasterId.'/root')
        ->setReturnType(Model\DriveItem::class)
        ->setPageSize(4);

        $page = $pages->getPage();



        while(!$pages->isEnd() ){
            if ($page->getName() == env('ONEDRIVE_REPOSITORY_NAME')){
                $RepositoryId = $page->getId();
                return $RepositoryId;
            }
            $page = $pages->getPage();

        }

        
        $RepositoryId = $page->getId();
                


        //

        return $RepositoryId;

    }
    public function _getRootMasterId($graph){
        $pages = $graph->createCollectionRequest('GET', '/users/'.$this->_UserId.'/drive')
        ->setReturnType(Model\DriveItem::class)
        ->setPageSize(4);

        $page = $pages->getPage();



        while(!$pages->isEnd() ){

        $page = $pages->getPage();

        }

        $RootMasterId = $page->getId();


                


        //

        return $RootMasterId;

    }

    public function getToken(){
        return $this->_token;
    }
    public function getAccessToken()
    {
        $body = "grant_type=".$this->grantType
                ."&scope=".$this->scope
                ."&client_id=".$this->clientId
                ."&client_secret=".$this->clientSecret;
                
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->contentType, 'Content-Length: ' . strlen($body)));

        $result = curl_exec ($ch);
        $token = json_decode($result, true)['access_token'];
        curl_close($ch);
        $this->_token = $token;
        return $token;
    }

    
}
