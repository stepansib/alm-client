<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 15.02.2016
 * Time: 14:48
 */

namespace StepanSib\AlmClient;

/**
 * Class AlmEntityLocker
 */
class AlmEntityLocker
{

    /** @var AlmCurl */
    protected $curl;

    /** @var AlmRoutes */
    protected $routes;

    /**
     * AlmEntityLocker constructor.
     * @param AlmCurl $curl
     * @param AlmRoutes $routes
     */
    public function __construct(AlmCurl $curl, AlmRoutes $routes)
    {
        $this->curl = $curl;
        $this->routes = $routes;
    }

    /**
     * @param $entity AlmEntity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function lockEntity(AlmEntity $entity)
    {
        $this->curl->setHeaders([
            'POST /HTTP/1.1',
            'Content-Type: application/xml',
            'Accept: application/xml',
        ]);

        $this->curl->setPost()
            ->createCookie()
            ->exec($this->routes->getEntityLockUrl($entity->getTypePluralized(), $entity->id));
    }

    /**
     * @param $entity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function unlockEntity(AlmEntity $entity)
    {
        $this->curl->setHeaders([
            'DELETE /HTTP/1.1',
            'Content-Type: application/xml',
            //'Accept: application/xml',
        ])
            ->setDelete()
            ->exec($this->routes->getEntityLockUrl($entity->getTypePluralized(), $entity->id));
    }

    /**
     * @param AlmEntity $entity
     * @return string
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function getEntityLockStatus(AlmEntity $entity)
    {
        $this->curl->exec($this->routes->getEntityLockUrl($entity->getTypePluralized(), $entity->id));
        $xml = simplexml_load_string($this->curl->getResult());
        return (string)$xml->LockStatus[0] . ' (' . (string)$xml->LockUser[0] . ', ' . (string)$xml->LockedByMe[0] . ')';
    }

    /**
     * @param AlmEntity $entity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function checkOutEntity(AlmEntity $entity)
    {
        $this->curl->setHeaders(['POST /HTTP/1.1'])
            ->setPost()
            ->exec($this->routes->getEntityCheckoutUrl($entity->getTypePluralized(), $entity->id));
    }

    /**
     * @param AlmEntity $entity
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function checkInEntity(AlmEntity $entity)
    {
        $this->curl->setHeaders(['POST /HTTP/1.1'])
            ->setPost()
            ->exec($this->routes->getEntityCheckinUrl($entity->getTypePluralized(), $entity->id));
    }

    /**
     * @param AlmEntity $entity
     * @return bool
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function isEntityLocked(AlmEntity $entity)
    {
        if (mb_substr_count($this->getEntityLockStatus($entity), 'UNLOCKED', 'utf-8') > 0) {
            return false;
        }
        return true;
    }

    /**
     * @param AlmEntity $entity
     * @return bool
     * @throws Exception\AlmCurlException
     * @throws Exception\AlmException
     */
    public function isEntityLockedByMe(AlmEntity $entity)
    {
        if (mb_substr_count($this->getEntityLockStatus($entity), 'LOCKED_BY_ME', 'utf-8') > 0) {
            return true;
        }
        return false;
    }

}
