<?php declare(strict_types = 1);

namespace App\Services\Ares;

/**
 * Service for retrieving company data from ARES registry
 * by company IN (identification number).
 */
class AresService
{
    private const ARES_URL = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/ares_es.cgi?jazyk=cz&maxpoc=1&ico=%s';

    public function getCompanyDataByIn(string $in): AresCompanyData
    {
        $simpleXml = $this->getXmlFromAresByIn($in);

        return $this->getCompanyDataFromAresXml($simpleXml, $in);
    }

    /**
     * @param \SimpleXMLElement $simpleXml
     * @param string $in
     * @return AresCompanyData
     * @throws Exceptions\CompanyNotFoundByInException
     */
    private function getCompanyDataFromAresXml(\SimpleXMLElement $simpleXml, string $in): AresCompanyData
    {
        $namespaces = $simpleXml->getDocNamespaces();
        $aresElement = $simpleXml->children($namespaces['are']);
        $dttElement = $aresElement->children($namespaces['dtt']);
        $vElement = $dttElement->V;
        $companyElement = $vElement->S;

        if (null === $companyElement || (string) $companyElement->ico !== $in) {
            throw new \App\Services\Ares\Exceptions\CompanyNotFoundByInException();
        }

        $tin = \ltrim((string) $companyElement->p_dph, 'dic=');

        return new AresCompanyData(
            (string) $companyElement->ico,
            $tin,
            (string) $companyElement->ojm,
            (string) $companyElement->jmn
        );
    }

    /**
     * @param string $in
     * @return \SimpleXMLElement
     * @throws \App\Services\Ares\Exceptions\AresApiNotAvailableException
     */
    private function getXmlFromAresByIn(string $in): \SimpleXMLElement
    {
        $aresUrl = self::getUrlForIn($in);
        $xmlString = self::getAresResponse($aresUrl);

        if (false === $xmlString)  {
            throw new \App\Services\Ares\Exceptions\AresApiNotAvailableException();
        }

        $simpleXmlElement = \simplexml_load_string($xmlString);

        if (false === $simpleXmlElement)  {
            throw new \App\Services\Ares\Exceptions\AresApiNotAvailableException();
        }

        return $simpleXmlElement;
    }

    private static function getAresResponse(string $aresUrl)
    {
        $curlHandle = \curl_init();
        \curl_setopt($curlHandle, \CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curlHandle, \CURLOPT_URL, $aresUrl);

        return \curl_exec($curlHandle);
    }

    private static function getUrlForIn(string $in): string
    {
        return \sprintf(self::ARES_URL, $in);
    }
}