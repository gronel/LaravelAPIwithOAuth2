<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImY3MDJhOTFiMjVjYzIzOWU3ODUyZGM2ZWIzYmQ3MGMyMjYxYWNjYjc0MjIyYmU3YjI4NjU4MjkwYjZjOTkzOWEyOWFmMTBiMWJkOWZhZmZjIn0.eyJhdWQiOiIzIiwianRpIjoiZjcwMmE5MWIyNWNjMjM5ZTc4NTJkYzZlYjNiZDcwYzIyNjFhY2NiNzQyMjJiZTdiMjg2NTgyOTBiNmM5OTM5YTI5YWYxMGIxYmQ5ZmFmZmMiLCJpYXQiOjE1MzY0NzYzNDIsIm5iZiI6MTUzNjQ3NjM0MiwiZXhwIjoxNTY4MDEyMzQyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.eCUML-J7wzVnesRX5Gxp9aIjBt6MP-tobzFSoIphqIdN2y3YViNk6YgZlHsxW8UVGOjqcz47VndrqR13KbrBJrBKJ_6T3LVgNaOQu_RY0cM-uyS7d8lzT9JyUYSWbhnuJZAEvZL6HURl53j2R5eim_fPOhj4aFQw6b7qVv4C5RrIEplCHBPcUh81eosd1UGQDU6GXSCmbtg1nGqa36-JZyWAYw3nMHfh5U-o2blChMBupuE0rYO8b0c2qfsNF8bD3JZpF5K7iweRMt5KStXxaDLq_RYMvLurAYKnTQNOCjOKZBaNy3ERVwZ2DSyjQcadicx2ysbK8IAhvYTGQfpXXJtY5xD57RtuMJfvV7SprZplbqnz9MuyMBoFhvYsgmfoU66zK1cF-BMNq0AkJFpjeXkPH1hxYZZLyyJRhmF_-mTwRlXP4SAsSEVoTwMgv1_n25AZYJMbpjsICzvcSOCpZg-drapfdgV0mFcbSHzxyZcUSrTbuDeTNoy7S8id5bbT6x2ekTn2qsXa5uIazEhfcBnelDMLrzC-sP7k0YzbSRLIDyRHXKCaM26Xrw4vruCr9Hrb6FIxk5RLEE6H3B1AbCinfxMl9xQqCuOrAVbENXQvRNc6ZtqmmoE3INkVFiFbOFMYL8lsVauujAZtCkelI2e3r-6i0Lf6jSbK_kYa6xY";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }



    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data=json_decode($this->responseBody);
        $count = count($data);
        if ($count == $arg1){

        }else{
            throw new Exception('Number of record not match');
        }
    }

    /**
     * @Then the question contains a title of :arg1
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if($data->title === $arg1) {

        } else {
            throw new Exception('the title does not match.');
        }
    }

}