<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Forum;
use Elastica\Client;
use Elastica\Type;
use Elastica\Query;
use Elastica\Search;
use Elastica\Document;

/**
 * Conection to elasticsearch Index throught elastica
 *
 * @author pedro
 */
class Database {
    private $elasticaClient,$elasticaIndex,$elasticaType;

    var $elasticaQuery,$searchQuery,$resultsSet;
    
    /**
     * Configures the index and type
     *
     */
   function __construct() {
       $this->elasticaClient = new Client();
       $this->elasticaIndex = $this->elasticaClient->getIndex('forum');
       
       $existsIndex = $this->elasticaIndex->exists();
       if (!$existsIndex){
           $this->elasticaIndex->create();
       }
       $this->elasticaType = $this->elasticaIndex->getType('images');
       $existsType = $this->elasticaType->exists();
       if (!$existsType){
           // Define mapping
           $mapping = new Type\Mapping();
           $mapping->setType($this->elasticaType);
           // Set mapping
           $mapping->setProperties(
                   array(
                       'imageName' => array('type' => 'string'),
                       'timestamp' => array('type' => 'date'),
                       'title' => array('type' => 'string')
                       )
                   );
           // Send mapping to type
           $mapping->send();           
       }
   }
      
   /**
     * launches query
     */
   function searchBase() {
       $this->elasticaQuery = new Query();
       $this->elasticaQuery->addSort(['timestamp' => 'desc']);
       
       $this->searchQuery = new Search($this->elasticaClient);
       $this->searchQuery
               ->addIndex($this->elasticaIndex) 
               ->addType($this->elasticaType);
       
       
       $this->resultsSet = $this->searchQuery->search($this->elasticaQuery);
       $this->elasticaQuery = new Query();
       $this->elasticaQuery->addSort(['timestamp' => 'desc']);
       
       $this->searchQuery = new Search($this->elasticaClient);
       $this->searchQuery
               ->addIndex($this->elasticaIndex) 
               ->addType($this->elasticaType);
   }
   
   /**
     * Gets all the results
     */
   function getAllResults() {
       $this->searchBase();
       return $this->resultsSet->getResults();
   }
   
   /**
     * Gets total hits
     */
   function getcount() {
       $this->searchBase();
       return $this->searchQuery->count();
       //return $this->resultsSet->getTotalHits();
   }
   
   /**
     * inputs data
     */
   function setDocument($title,$filename) {
       $photoPost = array(
           'timestamp'  => time(),
           'title'  => $title,
           'imageName'  => $filename
               );
       $imageDocument = new Document(null, $photoPost);
       $this->elasticaType->addDocument($imageDocument);
       $this->elasticaType->getIndex()->refresh();
   }
}
