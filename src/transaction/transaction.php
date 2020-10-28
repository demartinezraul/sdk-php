<?php

namespace StarkBank;

use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Transaction extends Resource
{
    /**
    # Transaction object

    A Transaction is a transfer of funds between workspaces inside Stark Bank.
    Transactions created by the user are only for internal transactions.
    Other operations (such as transfer or charge-payment) will automatically
    create a transaction for the user which can be retrieved for the statement.
    When you initialize a Transaction, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (required):
        - amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        - description [string]: text to be displayed in the receiver and the sender statements (Min. 10 characters). ex: "funds redistribution"
        - externalId [string]: unique id, generated by user, to avoid duplicated transactions. ex: "transaction ABC 2020-03-30"
        - receiverId [string]: unique id of the receiving workspace. ex: "5656565656565656"

    ## Parameters (optional):
        - tags [array of strings]: array of strings for reference when searching transactions (may be empty). ex: ["abc", "test"]

    ## Attributes (return-only):
        - senderId [string]: unique id of the sending workspace. ex: "5656565656565656"
        - source [string, default null]: locator of the entity that generated the transaction. ex: "charge/1827351876292", "transfer/92873912873/chargeback"
        - id [string, default null]: unique id returned when Transaction is created. ex: "7656565656565656"
        - fee [integer, default null]: fee charged when the transaction was created. ex: 200 (= R$ 2.00)
        - balance [integer, default null]: account balance after transaction was processed. ex: 100000000 (= R$ 1,000,000.00)
        - created [DateTime, default null]: creation datetime for the transaction.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->description = Checks::checkParam($params, "description");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->receiverId = Checks::checkParam($params, "receiverId");
        $this->senderId = Checks::checkParam($params, "senderId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->fee = Checks::checkParam($params, "fee");
        $this->source = Checks::checkParam($params, "source");
        $this->balance = Checks::checkParam($params, "balance");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create Transactions

    Send an array of Transaction objects for creation in the Stark Bank API

    ## Parameters (required):
        - transactions [array of Transaction objects]: array of Transaction objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - array of Transaction objects with updated attributes
     */
    public static function create($transactions, $user = null)
    {
        return Rest::post($user, Transaction::resource(), $transactions);
    }

    /**
    # Retrieve a specific Transaction

    Receive a single Transaction object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - Transaction object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Transaction::resource(), $id);
    }

    /**
    # Retrieve Transactions

    Receive a enumerator of Transaction objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - externalIds [array of strings, default null]: array of external ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Transaction objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Transaction::resource(), $options);
    }

    private static function resource()
    {
        $transaction = function ($array) {
            return new Transaction($array);
        };
        return [
            "name" => "Transaction",
            "maker" => $transaction,
        ];
    }
}
