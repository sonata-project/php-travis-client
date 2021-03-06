<?php

/*
 * This is part of php travis client
 *
 * (c) Leszek Prabucki <leszek.prabucki@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Travis;

use Travis\Client\Entity\BuildCollection,
    Travis\Client\Entity\Repository;

class Client
{
    /**
     * @var Buzz\Browser
     */
    private $browser;

    public function __construct()
    {
        $this->setBrowser(new \Buzz\Browser());
    }

    public function fetchRepository($slug)
    {
        $repositoryUrl = sprintf('http://travis-ci.org/%s.json', $slug);
        $buildsUrl = sprintf('http://travis-ci.org/%s/builds.json', $slug);

        $repository = new Repository();
        $repositoryArray = json_decode($this->browser->get($repositoryUrl)->getContent(), true);
        if (!$repositoryArray) {
            throw new \UnexpectedValueException(sprintf('Response is empty for url %s', $repositoryUrl));
        }
        $repository->fromArray($repositoryArray);

        $buildCollection = new BuildCollection(json_decode($this->browser->get($buildsUrl)->getContent(), true));
        $repository->setBuilds($buildCollection);

        return $repository;
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }
}
