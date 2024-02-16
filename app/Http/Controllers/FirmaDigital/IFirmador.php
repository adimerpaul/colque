<?php
declare(strict_types=1);

namespace App\Http\Controllers\FirmaDigital;

use DOMDocument;
use DOMNode;
use RuntimeException;

interface IFirmador
{
    /**
     * Algorithm identifiers.
     *
     * @see http://www.w3.org/TR/xmldsig-core/#sec-AlgID
     */
    /* Digest */
    /** @var string SHA1 Digest Algorithm URI */
    const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';

    /* Signature */
    /** @var string DSA with SHA1 (DSS) Sign Algorithm URI */
    const DSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#dsa-sha1';
    /** @var string RSA with SHA1 Sign Algorithm URI */
    const RSA_SHA256 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';

    /* Canonicalization */
    const XML_C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';

    /* Transform */
    const ENVELOPED = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    const WITHCOMMENTS = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments';
    /**
     * Set the private key for data sign.
     *
     * @param string $privateKey    Key in PEM format
     * @param string $algorithmType Key digest algorithm. By default RSA with SHA1
     *
     * @return void
     *
     * @see AdapterInterface::DSA_SHA1
     * @see AdapterInterface::RSA_SHA1
     */
    public function setPrivateKey($privateKey, $algorithmType = self::RSA_SHA256);

    /**
     * Set the public key.
     *
     * @param string $publicKey Key in PEM format
     *
     * @return void
     */
    public function setPublicKey($publicKey);

    /**
     * Returns the public key from various sources.
     *
     * Try to get the public key from the following sources (index means priority):
     *
     *  1) From $dom param of this method
     *  2) From a previous publickey set by setPublicKey
     *  3) From private key set by setPrivateKey
     *
     * @param null|DOMNode $dom DOM node where to search a publicKey
     *
     * @return string|null Public key in PEM format
     */
    public function getPublicKey(DOMNode $dom = null);

    /**
     * Public/Private key signature algorithm.
     *
     * @return string|null Algorithm URI
     */
    public function getKeyAlgorithm();

    /**
     * Set the digest algorithm.
     *
     * @param string $algorithmType Algorithm URI. By default SHA1
     *
     * @return void
     *
     * @see AdapterInterface::SHA1
     */
    public function setDigestAlgorithm($algorithmType = self::SHA256);

    /**
     * Canonicalization method.
     *
     * @param string $methodType Algorithm URI. By default C14N
     *
     * @return void
     *
     * @see AdapterInterface::XML_C14N
     */
    public function setCanonicalMethod($methodType = self::XML_C14N);

    /**
     * Add transform.
     *
     * @param string $transformType Transform URI
     *
     * @return void
     *
     * @see AdapterInterface::ENVELOPED
     */
    public function addTransform($transformType);

    /**
     * Add the "signature" element to the DOM Document.
     *
     * @param DOMDocument $data Data to sign
     *
     * @return void
     *
     * @throws RuntimeException If is not possible do the signature
     */
    public function sign(DOMDocument $data);

    /**
     * Validate the signature of the DOM Document.
     *
     * @param DOMDocument $data Data to verify
     *
     * @return bool TRUE if is correct or FALSE otherwise
     *
     * @throws RuntimeException If is not possible do the verification
     */
    public function verify(DOMDocument $data);
}
