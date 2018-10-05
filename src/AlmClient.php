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

    /**
     * AlmClient constructor.
     * @param array $connectionOptions
     * @throws Exception\AlmCurlCookieStorageException
     */
    public function __construct(array $connectionOptions)
    {

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->resolve($connectionOptions);

        $this->cookieStorage = new AlmCurlCookieStorage();
        $this->curl = new AlmCurl($this->cookieStorage, $connectionOptions);
        $this->routes = new AlmRoutes($connectionOptions['host'], $connectionOptions['domain'], $connectionOptions['project']);
        $this->authenticator = new AlmAuthenticator($connectionOptions['username'], $connectionOptions['password'], $this->curl, $this->cookieStorage, $this->routes);
        $this->manager = new AlmEntityManager($this->curl, $this->routes);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'domain',
            'project',
            'username',
            'password',
            'proxy_host',
            'proxy_port',
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
