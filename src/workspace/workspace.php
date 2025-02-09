<?php

namespace StarkBank;

use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Workspace extends Resource
{
    /**
    # Workspace object
    
    Workspaces are bank accounts. They have independent balances, statements, operations and permissions.
    The only property that is shared between your workspaces is that they are linked to your organization,
    which carries your basic informations, such as tax ID, name, etc..
    
    ## Parameters (required):
        - username [string]: Simplified name to define the workspace URL. This name must be unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - name [string]: Full name that identifies the Workspace. This name will appear when people access the Workspace on our platform, for example. Ex: "Stark Bank Workspace"
    
    ## Attributes:
        - id [string, default null]: unique id returned when the workspace is created. ex: "5656565656565656"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->username = Checks::checkParam($params, "username");
        $this->name = Checks::checkParam($params, "name");

        Checks::checkParams($params);
    }

    /**
    # Create Workspace
    
    Send a Workspace for creation in the Stark Bank API
    
    ## Parameters (required):
        - username [string]: Simplified name to define the workspace URL. This name must be unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - name [string]: Full name that identifies the Workspace. This name will appear when people access the Workspace on our platform, for example. Ex: "Stark Bank Workspace"
    
    ## Parameters (optional):
        - user [Organization object]: Organization object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - Workspace object with updated attributes
     */
    public static function create(array $params, $user = null)
    {
        return Rest::postSingle($user, Workspace::resource(), new Workspace($params));
    }

    /** 
    # Retrieve a specific Workspace
    
    Receive a single Workspace object previously created in the Stark Bank API by passing its id
    
    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - Workspace object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Workspace::resource(), $id);
    }

    /**
    # Retrieve Workspaces

    Receive a enumerator of Workspace objects previously created in the Stark Bank API.
    If no filters are passed and the user is an Organization, all of the Organization Workspaces
    will be retrieved.

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - username [string, default null]: query by the simplified name that defines the workspace URL. This name is always unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - enumerator of Workspace objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Workspace::resource(), $options);
    }

    private static function resource()
    {
        $workspace = function ($array){
            return new Workspace($array);
        };
        return [
            "name" => "Workspace",
            "maker" => $workspace,
        ];
    }
}
