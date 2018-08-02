<?php
/*
 * (c) lcavero <luiscaverodeveloper@gmail.com>
 */
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Exception\GithubServiceException;
use Github\Client;

class GithubService
{
    private $repository;

    private $user;
    private $rep;

    public function __construct()
    {
        $client = new Client();
        $this->repository = $client->repository();
    }

    /**
     * getWords
     * @param null $repository
     * @return array
     * @throws GithubServiceException
     * @throws \Exception
     * Get the words contained in all repository PHP classes name
     */
    public function getWords($repository = null)
    {
        $rep_data = $this->handleRepository($repository);

        $this->user = $rep_data[0];
        $this->rep = $rep_data[1];

        try{
            $files = $this->repository->contents()->show($this->user, $this->rep);
        }catch (\Exception $exception){
            if($exception->getMessage() == "Not Found"){
                throw new GithubServiceException("Repository not found");
            }else if($exception->getMessage() == "You have reached GitHub hourly limit! Actual limit is: 60"){
                throw new GithubServiceException("Max request limit reached");
            }else{
                throw $exception;
            }
        }

        return $this->processFiles($files);
    }

    /**
     * processFiles
     * @param $files
     * @param array $words
     * @return array
     *
     * Recursively process all tree structure from repository and extract the words of the classes names.
     */
    private function processFiles($files, &$words = [])
    {
        foreach ($files as $key => $item){
            if($item["type"] == "dir"){
                $dir_files = $this->repository->contents()->show(
                    $this->user, $this->rep, $item["path"]
                );
                $this->processFiles($dir_files, $words);
            }else{
                if($item["type"] == "file" && preg_match('/\.php$/', $item["name"])){
                    $this->extractWordsFormClassName($item["name"], $words);
                }
            }
        }
        return $words;
    }

    /**
     * extractWordsFormClassName
     * @param $className
     * @param $words
     * Analyze the class name and extract the words. Then put them in the words array
     */
    private function extractWordsFormClassName($className, &$words)
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $className,$matches);
        foreach ($matches[0] as $match){
            if(!isset($words[$match])){
                $words[$match] = 1;
            }else{
                $words[$match]++;
            }
        }
    }

    /**
     * handleRepository
     * @param null $repository
     * @return array
     * @throws GithubServiceException
     * Validate and handle the received repository
     */
    private function handleRepository($repository = null)
    {
        if(!$repository){
            throw new GithubServiceException("You should specify a repository");
        }else{
            $splt_rep = preg_split('/\//', $repository);
            if(count($splt_rep) != 2){
                throw new GithubServiceException("Invalid repository format: user/repository");
            }else{
                return $splt_rep;
            }
        }
    }

}