<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\StarkBankDate;

class Deposit extends Resource
{
    /**
    # Deposit object

    Deposits represent passive cash-in received by your account from external transfers

    ## Parameters (required):
        - id [string]: unique id associated with a Deposit when it is created. ex: "5656565656565656"
        - name [string]: payer name. ex: "Iron Bank S.A."
        - taxId [string]: payer tax ID (CPF or CNPJ). ex: "012.345.678-90" or "20.018.183/0001-80"
        - bankCode [string]: payer bank code in Brazil. ex: "20018183" or "341"
        - branchCode [string]: payer bank account branch. ex: "1357-9"
        - accountNumber [string]: payer bank account number. ex: "876543-2"
        - amount [integer]: Deposit value in cents. ex: 1234 (= R$ 12.34)
        - type [string]: type of settlement that originated the deposit. ex: "pix" or "ted"
        - status [string]: current Deposit status. ex: "created"
        - tags [list of strings]: list of strings that are tagging the deposit. ex: ["reconciliationId", "taxId"]
        - fee [integer]: fee charged when a deposit is created. ex: 50 (= R$ 0.50)
        - transactionIds [list of strings]: ledger transaction ids linked to this deposit (if there are more than one, all but first are reversals). ex: ["19827356981273"]
        - created [DateTime]: creation datetime for the Deposit. ex: "2020-03-10 10:30:00.000"
        - updated [DateTime]: latest update datetime for the Deposit. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent:: __construct($params);

        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->amount = Checks::checkParam($params, "amount");
        $this->type = Checks::checkParam($params, "type");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->fee = Checks::checkParam($params, "fee");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));


        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific Deposit

    Receive a single Deposit object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Deposit object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Deposit::resource(), $id);
    }

    /**
    # Retrieve Deposits

    Receive an enumerator of Deposit objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "created", "paid", "canceled" or "overdue"
        - sort [string, default "-created"]: sort order considered in response. Valid options are "created" or "-created".
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Deposit objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkBankDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkBankDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Deposit::resource(), $options);
    }

    private static function resource()
    {
        $deposit = function ($array) {
            return new Deposit($array);
        };
        return [
            "name" => "Deposit",
            "maker" => $deposit,
        ];
    }
}
