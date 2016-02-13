<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 12:43
 */

namespace StepanSib\AlmClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

Class AlmClient
{

    /** @var  AlmAuthenticator */
    protected $authenticator;

    /** @var  AlmCurl */
    protected $curl;

    /** @var  AlmCurlCookieStorage */
    protected $cookieStorage;

    /** @var  AlmRoutes */
    protected $routes;

    /** @var  AlmEntityManager */
    protected $manager;

    /** @var  AlmEntityMapper */
    protected $entityExtractor;

    /**
     * AlmClient constructor.
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions)
    {

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->resolve($connectionOptions);

        $this->entityExtractor = new AlmEntityMapper('StepanSib\AlmClient\AlmEntity', array(
            'id' => 'id',
            'user-05' => 'detectedBy',
            'owner' => 'owner',
            'name' => 'name',
            'description' => 'description',
            'dev-comments' => 'comments',
            'priority' => 'priority',
            'status' => 'status',
        ));

        $this->cookieStorage = new AlmCurlCookieStorage();
        $this->curl = new AlmCurl($this->cookieStorage);
        $this->routes = new AlmRoutes($connectionOptions['host'], $connectionOptions['domain'], $connectionOptions['project']);
        $this->authenticator = new AlmAuthenticator($connectionOptions['username'], $connectionOptions['password'], $this->curl, $this->cookieStorage, $this->routes);
        $this->manager = new AlmEntityManager($this->curl, $this->routes, $this->entityExtractor);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'host' => 'http://your.alm.server.com:8080',
            'domain' => 'your_domain',
            'project' => 'your_project_name',
            'username' => 'your_user_name',
            'password' => 'your_password',
        ))->setRequired(array(
            'host',
            'domain',
            'project',
            'username',
            'password',
        ));
    }

    /**
     * @return AlmAuthenticator
     */
    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    /**
     * @return AlmEntityMapper
     */
    public function getEntityExtractor()
    {
        return $this->entityExtractor;
    }

    /**
     * @return AlmCurl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @return AlmRoutes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return AlmCurlCookieStorage
     */
    public function getCookieStorage()
    {
        return $this->cookieStorage;
    }

    /**
     * @return AlmEntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

}
