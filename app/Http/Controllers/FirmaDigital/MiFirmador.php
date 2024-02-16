<?php

namespace App\Http\Controllers\FirmaDigital;
use DOMDocument;

class MiFirmador
{
    private static $instance;
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getAdapter()
    {
        return new Firmador();
    }

    protected function getPrivateKey()
    {
        return file_get_contents(storage_path("secret/privateKey.pem"));
    }

    protected function getPublicKey()
    {
        return file_get_contents(storage_path("secret/publicKey.pem"));
    }

    public function toSign($xmlPathName, $direccionXml1)
    {
        $adapter = $this->getAdapter();
        $data = new DOMDocument();
        $data1 = new DOMDocument();
        $data->load("$xmlPathName.xml");
        $data1->load("$direccionXml1.xml");

        $adapter->setPrivateKey($this->getPrivateKey());
        $adapter->setPublicKey($this->getPublicKey());
        $adapter->addTransform(IFirmador::ENVELOPED);
        $adapter->addTransform(IFirmador::WITHCOMMENTS);
        $adapter->setCanonicalMethod();
        $adapter->sign($data);
        $adapter->sign($data1);

        $outputFileXml = "$xmlPathName-signed.xml";
        $outputFileXml1 = "$direccionXml1-signed.xml";
        $data->save($outputFileXml);
        $data1->save($outputFileXml1);
        unlink("$direccionXml1.xml");
        return $outputFileXml;
    }
}
