<?php

namespace Biblys\Gleeph;

use Biblys\Exception\GleephAPIException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class GleephAPITest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testGetSimilarBooksByEan()
    {
        // given
        $gleeph = new GleephAPI("abcd1234");
        $httpClient = $this->createMock(ClientInterface::class);
        $response = new Response(200, [], '{ "book_ean13": "3260050120463", "similarsbooks": [ { "ean13": 9782378284923 }, { "ean13": 9782070374670 }, { "ean13": 9782410004793 } ] }');
        $httpClient
            ->method("sendRequest")
            ->with(new Request("GET", "https://data.gleeph.pro/v2.0.0/similarsbooks_byean?ean=9782715233324&nbrecos=10", ["x-api-key" => "abcd1234"]))
            ->willReturn($response);
        $gleeph->setHttpClient($httpClient);

        // when
        $similarBooks = $gleeph->getSimilarBooksByEan("9782715233324", 10);

        // then
        $this->assertEquals(
            ["9782378284923", "9782070374670", "9782410004793"],
            $similarBooks,
            "returns an array of EANs"
        );
    }


    /**
     * @throws ClientExceptionInterface
     */
    public function testGetSimilarBooksByEanInTestEnv()
    {
        // given
        $gleeph = new GleephAPI("abcd1234");
        $httpClient = $this->createMock(ClientInterface::class);
        $response = new Response(200, [], '{ "book_ean13": "3260050120463", "similarsbooks": [ { "ean13": 9782378284923 }, { "ean13": 9782070374670 }, { "ean13": 9782410004793 } ] }');
        $httpClient
            ->method("sendRequest")
            ->with(new Request("GET", "https://test.data.gleeph.pro/v2.0.0/similarsbooks_byean_test?ean=9782715233324&nbrecos=3", ["x-api-key" => "abcd1234"]))
            ->willReturn($response);
        $gleeph->setHttpClient($httpClient);
        $gleeph->setEnvironment("test");

        // when
        $similarBooks = $gleeph->getSimilarBooksByEan("9782715233324");

        // then
        $this->assertEquals(
            ["9782378284923", "9782070374670", "9782410004793"],
            $similarBooks,
            "returns an array of EANs"
        );
    }


    /**
     * @throws ClientExceptionInterface
     */
    public function testGetSimilarBooksErrorHandling()
    {
        // then
        $this->expectException(GleephAPIException::class);
        $this->expectExceptionMessage("INVALID_ARGUMENT:API key not valid. Please pass a valid API key.");

        // given
        $gleeph = new GleephAPI("invalid-api-key");
        $httpClient = $this->createMock(ClientInterface::class);
        $response = new Response(400, [], '{"code":400,"message":"INVALID_ARGUMENT:API key not valid. Please pass a valid API key."}');
        $httpClient
            ->method("sendRequest")
            ->with(new Request(
                "GET",
                "https://data.gleeph.pro/v2.0.0/similarsbooks_byean?ean=9782715233324&nbrecos=3",
                ["x-api-key" => "invalid-api-key"]
            ))
            ->willReturn($response);
        $gleeph->setHttpClient($httpClient);

        // when
        $gleeph->getSimilarBooksByEan("9782715233324");
    }
}
