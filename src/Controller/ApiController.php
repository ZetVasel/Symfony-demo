<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{

    protected $filename = '../base.csv';

    /**
     * @Route("/api", name="api_api")
     */
    public function api()
    {
        $response = new Response(
            $this->filename,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }

    /**
     * @Route("/api/{sku}", name="api_product")
     */
    public function product(Request $request, string $sku)
    {
       if ('application/json' !== $request->getContentType()) {
            return new Response('', 415);
        }
        $productData = $this->getProductBySky($sku);
        return new JsonResponse($productData);
    }

    private function getProductBySky(string $sku)
    {
        $handle = fopen($this->filename, "r");
        $fieldNameArray = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[0] == $sku) {
                $resultArray = [];
                foreach ($fieldNameArray as $key => $value) {
                    $resultArray[$value] = $data[$key];
                }

                return $resultArray;
            }
        }

        return NULL;
    }
}