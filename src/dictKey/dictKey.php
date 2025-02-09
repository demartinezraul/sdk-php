<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\StarkBankDate;

class DictKey extends Resource
{
    /**
    # DictKey object
     
    DictKey represents a PIX key registered in Bacen's DICT system.
    
    ## Parameters (required):
        - id [string]: DictKey object unique id and PIX key itself. ex: "tony@starkbank.com", "722.461.430-04", "20.018.183/0001-80", "+5511988887777", "b6295ee1-f054-47d1-9e90-ee57b74f60d9"
    
    ## Attributes (return-only):
        - type [string, default null]: DICT key type. ex: "email", "cpf", "cnpj", "phone" or "evp"
        - name [string, default null]: key owner full name. ex: "Tony Stark"
        - taxId [string, default null]: key owner tax ID (CNPJ or masked CPF). ex: "***.345.678-**" or "20.018.183/0001-80"
        - ownerType [string, default null]: DICT key owner type. ex "naturalPerson" or "legalPerson"
        - ispb [string, default null]: bank ISPB associated with the DICT key. ex: "20018183"
        - branchCode [string, default null]: bank account branch code associated with the DICT key. ex: "9585"
        - accountNumber [string, default null]: bank account number associated with the DICT key. ex: "9828282578010513"
        - accountType [string, default null]: bank account type associated with the DICT key. ex: "checking", "saving" e "salary"
        - status [string, default null]: current DICT key status. ex: "created", "registered", "canceled" or "failed"
        - accountCreated [DateTime, default null]: creation datetime of the bank account associated with the DICT key. ex: DateTime('2020-01-01T15:03:01.012345Z')
        - owned [DateTime, default null]: datetime since when the current owner holds this DICT key. ex: DateTime('2020-01-01T15:03:01.012345Z')
        - created [DateTime, default null]: creation datetime for the DICT key. ex: DateTime('2020-01-01T15:03:01.012345Z')
     */
    function __construct(array $params)
    {
        parent:: __construct($params);
        
        $this->type = Checks::checkParam($params, "type");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->ownerType = Checks::checkParam($params, "ownerType");
        $this->ispb = Checks::checkParam($params, "ispb");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->accountCreated = Checks::checkDateTime(Checks::checkParam($params, "accountCreated"));
        $this->status = Checks::checkParam($params, "status");
        $this->owned = Checks::checkDateTime(Checks::checkParam($params, "owned"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }
  
    /**
    # Retrieve a specific DictKey

    Receive a single DictKey object by passing its id

    ## Parameters (required):
        - id [string]: DictKey object unique id and PIX key itself. ex: 'tony@starkbank.com', '722.461.430-04', '20.018.183/0001-80', '+5511988887777', 'b6295ee1-f054-47d1-9e90-ee57b74f60d9'

    ## Parameters (optional):
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - DictKey object with updated attributes
        */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, DictKey::resource(), $id);
    }

    /**
    # Retrieve DictKeys

    Receive an enumerator of DictKey objects associated with your Stark Bank Workspace

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - type [string, default null]: DictKey type. ex: "cpf", "cnpj", "phone", "email" or "evp"
        - after [datetime.date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [datetime.date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default None]: filter for status of retrieved objects. ex: "success"
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of DictKey objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkBankDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkBankDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, DictKey::resource(), $options);
    }
    
    private static function resource()
    {
        $dictKey = function ($array) {
            return new DictKey($array);
        };
        return [
            "name" => "DictKey",
            "maker" => $dictKey,
        ];
    }
}
