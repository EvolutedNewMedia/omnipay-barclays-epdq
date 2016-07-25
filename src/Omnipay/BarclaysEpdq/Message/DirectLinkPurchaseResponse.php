<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\BarclaysEpdq\Message\EssentialPurchaseResponse;

/**
 * BarclaysEpdq Purchase Response
 */
class DirectLinkPurchaseResponse extends EssentialCompletePurchaseResponse implements RedirectResponseInterface
{
    public $unparsedData = null;

    /**
     * Constructor
     *
     * @param RequestInterface $request the initiating request.
     * @param mixed $data
     */
    public function __construct(DirectLinkPurchaseRequest $request, $data)
    {
        parent::__construct($request, $data);

        $this->unparsedData = $data;

        //  Convert XML object to Array 
        $this->data = $this->xmlAttributesToArray($data->attributes());

        // Extract the HTML Answer 
        if (!empty($data->HTML_ANSWER)) {
            $this->data['HTML_ANSWER'] = $data->HTML_ANSWER;
        }
    }

    public function isRedirect()
    {
        return $this->data['STATUS'] == 46;
    }

    public function getRedirectUrl()
    {
        // var_dump($this->getData());
        // var_dump($this->unparsedData);
        die('redirecting');
        return $this->getRequest()->getEndpoint();
    }

    public function redirect()
    {
        // Output redirect code
        $data = $this->getData();
        echo base64_decode($data['HTML_ANSWER']);
        die();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }

    public function getTransactionReference()
    {
        return $this->data['PAYID'];
    }

    /**
     * Convert XML into Array 
     * @param  SimpleXML $attributes Response from payment gateway
     * @return array             Response in array form
     */
    private function xmlAttributesToArray($attributes)
    {
        $attributesArray = array();

        foreach ($attributes as $key => $value) {
            $attributesArray[(string)$key] = (string)$value;
        }
        

        return $attributesArray;
    }
}
