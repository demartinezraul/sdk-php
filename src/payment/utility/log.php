<?php

namespace StarkBank\UtilityPayment;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;
use StarkBank\UtilityPayment;


class Log extends Resource
{
    /**
    # UtilityPayment\Log object

    Every time a UtilityPayment entity is modified, a corresponding UtilityPayment\Log
    is generated for the entity. This log is never generated by the user, but it can
    be retrieved to check additional information on the UtilityPayment.

    ## Attributes:
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - payment [UtilityPayment]: UtilityPayment entity to which the log refers to.
        - errors [list of strings]: list of errors linked to this BoletoPayment event.
        - type [string]: type of the UtilityPayment event which triggered the log creation. ex: "registered" or "paid"
        - created [DateTime]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);
        $this->type = $params["type"];
        unset($params["type"]);
        $this->errors = $params["errors"];
        unset($params["errors"]);
        $this->payment = $params["payment"];
        unset($params["payment"]);

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific Log

    Receive a single Log object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Log object with updated attributes
     */
    public function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve ogs

    Receive a enumerator of Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime, default null] date filter for objects created only after specified date.
        - before [DateTime, default null] date filter for objects only before specified date.
        - types [list of strings, default null]: filter retrieved objects by event types. ex: "paid" or "registered"
        - paymentIds [list of strings, default null]: list of UtilityPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Log objects with updated attributes
     */
    public function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, Log::resource(), $options);
    }

    private function resource()
    {
        $paymentLog = function ($array) {
            $payment = function ($array) {
                return new UtilityPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new Log($array);
        };
        return [
            "name" => "UtilityPaymentLog",
            "maker" => $paymentLog,
        ];
    }
}
