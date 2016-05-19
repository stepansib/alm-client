## HP ALM/QC REST Client   

[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/03787473-bf91-4436-9173-5c395cea50d3.svg)](https://insight.sensiolabs.com/projects/03787473-bf91-4436-9173-5c395cea50d3)
[![Codacy branch](https://img.shields.io/codacy/1c4d056c8029418b8ffaf377994e96ce/master.svg)](https://www.codacy.com/app/stepan-sib/alm-client)
[![Packagist](https://img.shields.io/packagist/v/stepansib/alm-client.svg)](https://packagist.org/packages/stepansib/alm-client)
[![Coveralls](https://img.shields.io/coveralls/stepansib/alm-client.svg)](https://coveralls.io/github/stepansib/alm-client)

Easily interact with HP ALM using REST API. 

##Installation
Simply run
```bash
composer require stepansib/alm-client
```

##Usage
###Setting up ALM/QC connection
The first step is to setup correct connection credentials and instantiate new AlmClient object
```php
$almClient = new AlmClient(array(
    'host' => 'http://alm-qc-host:8080',
    'domain' => 'DOMAIN_NAME',
    'project' => 'PROJECT_NAME',
    'username' => 'johndoe',
    'password' => 'password123',
));
```

###Authentication
You need to authenticate to start work with ALM
```php
$almClient = new AlmClient($connectionParams);
$almClient->getAuthenticator()->login();

// lets check if user authenticated successfully
echo $almClient->getAuthenticator()->isAuthenticated() ? "Authenticated" : "Not authenticated";
```

When you finish your work with ALM/QC use logout method
```php
$almClient->getAuthenticator()->logout();
```

###Get entity by criteria
All entities are returned in AlmEntity objects. You must specify ALM/QC entity type (defect, test, run etc) and array of criterias to filter entites
```php
// you can get the array of entities
$defects = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'id' => '>=100',
    'status' => 'Open',
    'owner' => 'johndoe',
));

// or get only first matching entity
$entity = $almClient->getManager()->getOneBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'id' => '101'
));
```
Also you can get entities as ALM/QC XML response by specifying hydration type:
```php
$defects = $almClient->getManager()->getBy(AlmEntityManager::ENTITY_TYPE_DEFECT, array(
    'owner' => 'johndoe',
), AlmEntityManager::HYDRATION_NONE);

// Lets output the XML returned by ALM/QC
echo $defects;
```

### The entity
Entity field values can be accessed in two ways
```php
// through getter method
$paramValue = $entity->getParameter('detected-by');

//or directly via magic getter method 
$paramValue = $entity->detected-by;
```

To create a new parameter or change the existing parameter use setter method 
```php
$entity->setParameter('description', 'my defect description');
```

To get all parameters in array use 
```php
$entityParameters = $entity->getParameters();
```

To get and change entity type use
```php
$entityType = $entity->getType();
$entity->setType(AlmEntityManager::ENTITY_TYPE_RESOURCE); //This method also called in AlmEntity::__construct
```

### Create a new entity
To create a new entity you have to instantiate an AlmEntity object
```php
$entity = new AlmEntity(AlmEntityManager::ENTITY_TYPE_DEFECT);
```

###Save an entity
To save (persist or update) an entity use the `AlmEntityManager::save()` method
```php
$almClient->getManager()->save($entity);
```
This will work both for new and existing entities. This method returns saved AlmEntity object

Full workflow example
```php
$entity = new AlmEntity(AlmEntityManager::ENTITY_TYPE_DEFECT);

// lets fill some entity fields
$entity->setParameter('name', 'REST API test defect ' . date('d/m/Y H:i:s'))
    ->setParameter('detected-by', 'johndoe')
    ->setParameter('owner', 'johndoe')
    ->setParameter('creation-time', date('Y-m-d'))
    ->setParameter('description', 'REST API test defect description');

// and finally save the new defect
$entity = $almClient->getManager()->save($entity);
echo 'New entity id: ' . $entity->id;
```

### Get entity available fields list (and editable fields list)
Todo: complete this chapter

### Get editable fields list
Todo: complete this chapter

### Get entity lock state
Todo: complete this chapter
