# Stark Bank PHP SDK

Welcome to the Stark Bank PHP SDK! This tool is made for PHP
developers who want to easily integrate with our API.
This SDK version is compatible with the Stark Bank API v2.

If you have no idea what Stark Bank is, check out our [website](https://www.starkbank.com/)
and discover a world where receiving or making payments
is as easy as sending a text message to your client!

## Supported PHP Versions

This library supports the following PHP versions:

* PHP 7.1
* PHP 7.2
* PHP 7.3
* PHP 7.4

## Stark Bank API documentation

Feel free to take a look at our [API docs](https://www.starkbank.com/docs/api).

## Versioning

This project adheres to the following versioning pattern:

Given a version number MAJOR.MINOR.PATCH, increment:

- MAJOR version when the **API** version is incremented. This may include backwards incompatible changes;
- MINOR version when **breaking changes** are introduced OR **new functionalities** are added in a backwards compatible manner;
- PATCH version when backwards compatible bug **fixes** are implemented.

## Setup

### 1. Install our SDK

1.1 Composer: To install the package with Composer, run:

```sh
composer require starkbank/sdk
```

To use the bindings, use Composer's autoload:

```sh
require_once('vendor/autoload.php');
```

1.2 Manual installation: You can also download the latest release from GitHub and then, to use the bindings, include the init.php file.

```sh
require_once('/path/to/starkbank/sdk-php/init.php');
```

In manual installations, you will also need to get the following dependency:
- [starkbank/ecdsa](https://github.com/starkbank/ecdsa-php)


### 2. Create your Private and Public Keys

We use ECDSA. That means you need to generate a secp256k1 private
key to sign your requests to our API, and register your public key
with us so we can validate those requests.

You can use one of following methods:

2.1. Check out the options in our [tutorial](https://starkbank.com/faq/how-to-create-ecdsa-keys).

2.2. Use our SDK:

```php
use StarkBank\Key;

list($privateKey, $publicKey) = Key::create();

# or, to also save .pem files in a specific path
list($privateKey, $publicKey) = Key::create("file/keys/");
```
NOTE: When you are creating new credentials, it is recommended that you create the
keys inside the infrastructure that will use it, in order to avoid any risky internet
transmissions of your **private-key**. Then you can export the **public-key** alone to the
computer where it will be used in the new Project creation.

### 3. Register your user credentials

You can interact directly with our API using two types of users: Projects and Organizations.

- **Projects** are workspace-specific users, that is, they are bound to the workspaces they are created in.
One workspace can have multiple Projects.
- **Organizations** are general users that control your entire organization.
They can control all your Workspaces and even create new ones. The Organization is bound to your company's tax ID only.
Since this user is unique in your entire organization, only one credential can be linked to it.

3.1. To create a Project in Sandbox:

3.1.1. Log into [Starkbank Sandbox](https://web.sandbox.starkbank.com)

3.1.2. Go to Menu > Integrations

3.1.3. Click on the "New Project" button

3.1.4. Create a Project: Give it a name and upload the public key you created in section 2

3.1.5. After creating the Project, get its Project ID

3.1.6. Use the Project ID and private key to create the object below:

```php
use StarkBank\Project;

// Get your private key from an environment variable or an encrypted database.
// This is only an example of a private key content. You should use your own key.
$privateKeyContent = "
-----BEGIN EC PARAMETERS-----
BgUrgQQACg==
-----END EC PARAMETERS-----
-----BEGIN EC PRIVATE KEY-----
MHQCAQEEIMCwW74H6egQkTiz87WDvLNm7fK/cA+ctA2vg/bbHx3woAcGBSuBBAAK
oUQDQgAE0iaeEHEgr3oTbCfh8U2L+r7zoaeOX964xaAnND5jATGpD/tHec6Oe9U1
IF16ZoTVt1FzZ8WkYQ3XomRD4HS13A==
-----END EC PRIVATE KEY-----
";

$project = new Project([
    "environment" => "sandbox",
    "id" => "5656565656565656",
    "privateKey" => $privateKeyContent
]);
```

3.2. To create Organization credentials in Sandbox:

3.2.1. Log into [Starkbank Sandbox](https://web.sandbox.starkbank.com)

3.2.2. Go to Menu > Integrations

3.2.3. Click on the "Organization public key" button

3.2.4. Upload the public key you created in section 2 (only a legal representative of the organization can upload the public key)

3.2.5. Click on your profile picture and then on the "Organization" menu to get the Organization ID

3.2.6. Use the Organization ID and private key to create the object below:

```php
use StarkBank\Organization;

// Get your private key from an environment variable or an encrypted database.
// This is only an example of a private key content. You should use your own key.
privateKeyContent = "
-----BEGIN EC PARAMETERS-----
BgUrgQQACg==
-----END EC PARAMETERS-----
-----BEGIN EC PRIVATE KEY-----
MHQCAQEEIMCwW74H6egQkTiz87WDvLNm7fK/cA+ctA2vg/bbHx3woAcGBSuBBAAK
oUQDQgAE0iaeEHEgr3oTbCfh8U2L+r7zoaeOX964xaAnND5jATGpD/tHec6Oe9U1
IF16ZoTVt1FzZ8WkYQ3XomRD4HS13A==
-----END EC PRIVATE KEY-----
";

$organization = new Organization([
    "environment" => "sandbox",
    "id" => "5656565656565656",
    "privateKey" => $privateKeyContent,
    "workspaceId" => null // You only need to set the workspaceId when you are operating a specific workspaceId
]);

// To dynamically use your organization credentials in a specific workspaceId,
// you can use the Organization::replace() method:
$balance = Balance::get(Organization::replace($organization, "4848484848484848"));
```

NOTE 1: Never hard-code your private key. Get it from an environment variable or an encrypted database.

NOTE 2: We support `'sandbox'` and `'production'` as environments.

NOTE 3: The credentials you registered in `sandbox` do not exist in `production` and vice versa.


### 4. Setting up the user

There are three kinds of users that can access our API: **Organization**, **Project** and **Member**.

- `Project` and `Organization` are designed for integrations and are the ones meant for our SDKs.
- `Member` is the one you use when you log into our webpage with your e-mail.

There are two ways to inform the user to the SDK:

4.1 Passing the user as argument in all functions:

```php
use StarkBank\Balance;

$balance = Balance::get($project);  # or organization
```

4.2 Set it as a default user in the SDK:

```php
use StarkBank\Settings;
use StarkBank\Balance;

Settings::setUser($project);  # or organization

$balance = Balance::get();
```

Just select the way of passing the user that is more convenient to you.
On all following examples we will assume a default user has been set.

### 5. Setting up the error language

The error language can also be set in the same way as the default user:

```php
use StarkBank\Settings;

Settings::setLanguage("en-US");
```

Language options are "en-US" for english and "pt-BR" for brazilian portuguese. English is default.


## Testing in Sandbox

Your initial balance is zero. For many operations in Stark Bank, you'll need funds
in your account, which can be added to your balance by creating an Invoice or a Boleto. 

In the Sandbox environment, most of the created Invoices and Boletos will be automatically paid,
so there's nothing else you need to do to add funds to your account. Just create
a few Invoices and wait around a bit.

In Production, you (or one of your clients) will need to actually pay this Invoice or Boleto
for the value to be credited to your account.


## Usage

Here are a few examples on how to use the SDK. If you have any doubts, check out
the function or class docstring to get more info or go straight to our [API docs].

### Create transactions

To send money between Stark Bank accounts, you can create transactions:

```php
use StarkBank\Transaction;

$transactions = Transaction::create([
    new Transaction([
        "amount" => 100,  # (R$ 1.00)
        "receiverId" => "1029378109327810",
        "description" => "Transaction to dear provider",
        "externalId" => "12345",  # so we can block anything you send twice by mistake
        "tags" => ["provider"]
    ]),
    new Transaction([
        "amount" => 234,  # (R$ 2.34)
        "receiverId" => "2093029347820947",
        "description" => "Transaction to the other provider",
        "externalId" => "12346",  # so we can block anything you send twice by mistake
        "tags" => ["provider"]
    ]),
]);

foreach($transactions as $transaction){
    print_r($transaction);
}
```

**Note**: Instead of using Transaction objects, you can also pass each transaction element directly in array format, without using the constructor

### Query transactions

To understand your balance changes (bank statement), you can query
transactions. Note that our system creates transactions for you when
you receive boleto payments, pay a bill or make transfers, for example.

```php
use StarkBank\Transaction;

$transactions = Transaction::query([
    "after" => "2020-01-01",
    "before" => "2020-03-01"
]);

foreach($transactions as $transaction){
    print_r($transaction);
}
```

### Get a transaction

You can get a specific transaction by its id:

```php
use StarkBank\Transaction;

$transaction = Transaction::get("5155165527080960");

print_r($transaction);
```

### Get your balance

To know how much money you have in your workspace, run:

```php
use StarkBank\Balance;

$balance = Balance::get();

print_r($balance);
```

### Create transfers

You can also create transfers in the SDK (TED/Pix).

```php
use StarkBank\Transfer;

$transfers = Transfer::create([
    new Transfer([
        "amount" => 100,
        "bankCode" => "033",  # TED
        "branchCode" => "0001",
        "accountNumber" => "10000-0",
        "taxId" => "012.345.678-90",
        "name" => "Tony Stark",
        "tags" => ["iron", "suit"]
    ]),
    new Transfer([
        "amount" => 200,
        "bankCode" => "20018183",  # Pix
        "branchCode" => "1234",
        "accountNumber" => "123456-7",
        "accountType" => "salary",
        "externalId" => "my-internal-id-12345",
        "taxId" => "012.345.678-90",
        "name" => "Jon Snow",
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D")),
        "tags" => []
    ])
]);

foreach($transfers as $transfer){
    print_r($transfer);
}
```

**Note**: Instead of using Transfer objects, you can also pass each transfer element directly in array format, without using the constructor

### Query transfers

You can query multiple transfers according to filters.

```php
use StarkBank\Transfer;

$transfers = Transfer::query([
    "after" => "2020-01-01",
    "before" => "2020-04-01"
]);

foreach($transfers as $transfer){
    print_r($transfer->name);
}
```

### Get a transfer

To get a single transfer by its id, run:

```php
use StarkBank\Transfer;

$transfer = Transfer::get("5155165527080960");

print_r($transfer);
```

### Cancel a scheduled transfer

To cancel a single scheduled transfer by its id, run:

```php
use StarkBank\Transfer;

$transfer = Transfer::delete("5155165527080960");

print_r($transfer);
```

### Get a transfer PDF

A transfer PDF may also be retrieved by passing its id.
This operation is only valid if the transfer status is "processing" or "success".

```php
use StarkBank\Transfer;

$pdf = Transfer::pdf("5155165527080960");

$fp = fopen('transfer.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Query transfer logs

You can query transfer logs to better understand transfer life cycles.

```php
use StarkBank\Transfer;

$logs = Transfer\Log::query(["limit" => 50]);

foreach($logs as $log){
    print_r($log->id);
}
```

### Get a transfer log

You can also get a specific log by its id.

```php
use StarkBank\Transfer;

$log = Transfer\Log::get("5155165527080960");

print_r($log);
```

### Create invoices

You can create dynamic QR Code invoices to charge customers or to receive money from accounts you have in other banks.

```php
use StarkBank\Invoice;

$invoices = [
    new Invoice([
        "amount" => 400000,
        "due" => ((new DateTime("now"))->add(new DateInterval("P5D"))),
        "taxId" => "012.345.678-90",
        "name" => "Mr Meeseks",
        "expiration" => new DateInterval("P2D"),
        "fine" => 2.5,
        "interest" => 1.3,
        "discounts" => [
            [
                "percentage" => 5,
                "due" => ((new DateTime("now"))->add(new DateInterval("P1D")))
            ],
            [
                "percentage" => 3,
                "due" => ((new DateTime("now"))->add(new DateInterval("P2D")))
            ]
        ],
        "tags" => [
            'War supply',
            'Invoice #1234'
        ],
        "descriptions" => [
            [
                "key" => "product A",
                "value" => "big"
            ],
            [
                "key" => "product B",
                "value" => "medium"
            ],
            [
                "key" => "product C",
                "value" => "small"
            ]
        ],
    ])
];

$invoice = Invoice::create($invoices)[0];

print_r($invoice);
```
**Note**: Instead of using Invoice objects, you can also pass each invoice element directly in array format, without using the constructor

### Get an invoice

After its creation, information on an invoice may be retrieved by its id.
Its status indicates whether it's been paid.

```php
use StarkBank\Invoice;

$invoice = Invoice::get("5656565656565656");

print_r($invoice);
```

### Get an invoice QR Code 

After its creation, an Invoice QR Code may be retrieved by its id. 

```php
use StarkBank\Invoice;

$png = Invoice::qrcode("5881614903017472");

$fp = fopen('qrcode.png', 'w');
fwrite($fp, $png);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw png content,
as it may corrupt the file.

### Get an invoice PDF

After its creation, an invoice PDF may be retrieved by its id.

```php
use StarkBank\Invoice;

$pdf = Invoice::pdf("5656565656565656");

$fp = fopen('invoice.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Cancel an invoice

You can also cancel an invoice by its id.
Note that this is not possible if it has been paid already.

```php
use StarkBank\Invoice;

$invoice = Invoice::update("5656565656565656", ["status" => "canceled"]);

print_r($invoice);
```

### Update an invoice

You can update an invoice's amount, due date and expiration by its id.
Note that this is not possible if it has been paid already.

```php
use StarkBank\Invoice;

$updatedInvoice = Invoice::update(
    "5656565656565656",
    [
        "amount" => 4321,
        "due" => (new DateTime("now"))->add(new DateInterval("P5D")),
        "expiration" => 123456789
    ]
);

print_r($updatedInvoice);
```

### Query invoices

You can get a list of created invoices given some filters.

```php
use StarkBank\Invoice;

$invoices = iterator_to_array(Invoice::query(["limit" => 10, "before" => new DateTime("now")]));

foreach($invoices as $invoice) {
    print_r($invoice);
}
```

### Get an invoice payment information

Once an invoice has been paid, you can get the payment information using the Invoice.Payment sub-resource:

```php
use StarkBank\Invoice;

$paymentInformation = Invoice::payment("5656565656565656");

print_r($paymentInformation);
```

### Query invoice logs

Logs are pretty important to understand the life cycle of an invoice.

```php
use StarkBank\Invoice\Log;

$invoiceLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

foreach($invoiceLogs as $log) {
    print_r($log);
}
```

### Get an invoice log

You can get a single log by its id.

```php
use StarkBank\Invoice\Log;

$invoiceLog = Log::get("5656565656565656");

print_r($invoice);
```

### Query deposits

You can get a list of created deposits given some filters.

```php
use StarkBank\Deposit;

$deposits = iterator_to_array(Deposit::query(["limit" => 10, "before" => new DateTime("now")]));

foreach($deposits as $deposit) {
    print_r($deposit);
}
```

### Get a deposit

After its creation, information on a deposit may be retrieved by its id. 

```php
use StarkBank\Deposit;

$deposit = Deposit::get("5656565656565656");

print_r($deposit);
```

### Query deposit logs

Logs are pretty important to understand the life cycle of a deposit.

```php
use StarkBank\Deposit\Log;

$depositLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

foreach($depositLogs as $log) {
    print_r($log);
}
```

### Get a deposit log

You can get a single log by its id.

```php
use StarkBank\Deposit\Log;

$depositLog = Log::get("5656565656565656");

print_r($deposit);
```

### Create boletos

You can create boletos to charge customers or to receive money from accounts
you have in other banks.

```php
use StarkBank\Boleto;


$boletos = Boleto::create([
    new Boleto([
        "amount" => 23571,  # R$ 235,71
        "name" => "Buzz Aldrin",
        "taxId" => "012.345.678-90",
        "streetLine1" => "Av. Paulista, 200",
        "streetLine2" => "10 andar",
        "district" => "Bela Vista",
        "city" => "São Paulo",
        "stateCode" => "SP",
        "zipCode" => "01310-000",
        "due" => (new DateTime("now"))->add(new DateInterval("P30D")),
        "fine" => 5,  # 5%
        "interest" => 2.5  # 2.5% per month
    ])
]);

foreach($boletos as $boleto){
    print_r($boleto);
}
```

**Note**: Instead of using Boleto objects, you can also pass each boleto element directly in array format, without using the constructor


### Get a boleto

After its creation, information on a boleto may be retrieved by passing its id.
Its status indicates whether it's been paid.

```php
use StarkBank\Boleto;

$boleto = Boleto::get("5155165527080960");

print_r($boleto);
```

### Get a boleto PDF

After its creation, a boleto PDF may be retrieved by passing its id.

```php
use StarkBank\Boleto;

$pdf = Boleto::pdf("5155165527080960", ["layout" => "default"]);

$fp = fopen('boleto.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete a boleto

You can also cancel a boleto by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\Boleto;

$boleto = Boleto::delete("5155165527080960");

print_r($boleto);
```

### Query boletos

You can get an array of created boletos given some filters.

```php
use StarkBank\Boleto;

$boletos = Boleto::query([
    "after" => "2020-01-01",
    "before" => (new DateTime("now"))->add(new DateInterval("P1D"))
]);

foreach($boletos as $boleto){
    print_r($boleto);
}
```

### Query boleto logs

Logs are pretty important to understand the life cycle of a boleto.

```php
use StarkBank\Boleto;

$logs = Boleto\Log::query(["limit" => 150]);

foreach($logs as $log){
    print_r($log);
}
```

### Get a boleto log

You can get a single log by its id.

```php
use StarkBank\Boleto;

$log = Boleto\Log::get("5155165527080960");

print_r($log);
```

### Investigate a boleto

You can discover if a StarkBank boleto has been recently paid before we receive the response on the next day.
This can be done by creating a BoletoHolmes object, which fetches the updated status of the corresponding
Boleto object according to CIP to check, for example, whether it is still payable or not. The investigation
happens asynchronously and the most common way to retrieve the results is to register a "boleto-holmes" webhook
subscription, although polling is also possible. 

```php
use StarkBank\BoletoHolmes;

$holmes = [new BoletoHolmes([
    "boletoId" => "5976467733217280"
])];

$sherlock = BoletoHolmes::create($holmes)[0];

foreach($holmes as $sherlock){
    print_r($sherlock);
}
```

**Note**: Instead of using BoletoHolmes objects, you can also pass each payment element directly in array format, without using the constructor

### Get a boleto holmes

To get a single Holmes by its id, run:

```php
use StarkBank\BoletoHolmes;
$sherlock = Boleto::get("5976467733217280");
print_r($sherlock)
```

### Query boleto holmes

You can search for boleto Holmes using filters. 

```php
use StarkBank\BoletoHolmes;
$holmes = iterator_to_array(Boleto::query(["limit" => 10, "before" => new DateTime("now")]));

foreach($holmes as $sherlock){
    print_r($sherlock);
}
```

### Query boleto holmes logs

Searches are also possible with boleto holmes logs:

```php
use StarkBank\BoletoHolmes\Log;
$logs = iterator_to_array(Log::query(["limit" => 10, "types" => ["solving"]]));

foreach($logs as $log){
    print_r($log);
}
```

### Get a boleto holmes log

You can also get a boleto holmes log by specifying its id.

```php
use StarkBank\BoletoHolmes\Log;
$log = Log::get("5976467733217280");
print_r($log)
```

### Preview a BR Code payment

You can confirm the information on the BR Code payment before creating it with this preview method:

```php
use StarkBank\BrcodePreview;

$previews = BrcodePreview::query([
    "brcodes" => ["00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A"],
]);

foreach($previews as $preview){
    print_r($preview);
}
```

### Pay a BR Code

Paying a BR Code is also simple.

```php
use StarkBank\BrcodePayment;

$payments = BrcodePayment::create([
    new BrcodePayment([
        "brcode" => "00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A",
        "taxId" => "20.018.183/0001-80",
        "description" => "Tony Stark's Suit",
        "amount" => 7654321,
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P5D")),
        "tags" => ["Stark", "Suit"]
    ])
]);

foreach($payments as $payment){
    print_r($payment);
}
```

**Note**: Instead of using BrcodePayment objects, you can also pass each payment element directly in array format, without using the constructor

### Get a BR Code payment

To get a single BR Code payment by its id, run:

```php
use StarkBank\BrcodePayment;

$payment = BrcodePayment::get("19278361897236187236");

print_r($payment);
```

### Get a BR Code payment PDF

After its creation, a BR Code payment PDF may be retrieved by its id. 

```php
use StarkBank\BrcodePayment;

$pdf = BrcodePayment::pdf("5155165527080960");

$fp = fopen('brcodePayment.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Query BR Code payments

You can search for BR Code payments using filters. 

```php
use StarkBank\BrcodePayment;

$payments = BrcodePayment::query([
    "tags" => ["company_1", "company_2"]
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Query BR Code payment logs

Searches are also possible with BR Code payment logs:

```php
use StarkBank\BrcodePayment;

$logs = BrcodePayment\Log::query([
    "paymentIds" => ["5155165527080960", "76551659167801921"],
]);

foreach($logs as $log){
    print_r($log);
}
```

### Get a BR Code payment log

You can also get a BR Code payment log by specifying its id.

```php
use StarkBank\BrcodePayment;

$log = BrcodePayment\Log::get("5155165527080960");

print_r($log);
```

### Pay a boleto

Paying a boleto is also simple.

```php
use StarkBank\BoletoPayment;

$payments = BoletoPayment::create([
    new BoletoPayment([
        "line" => "34191.09008 64694.017308 71444.640008 1 96610000014500",
        "taxId" => "012.345.678-90",
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P2D")),
        "description" => "take my money",
        "tags" => ["take", "my", "money"],
    ]),
    new BoletoPayment([
        "barCode" => "34191972300000289001090064694197307144464000",
        "taxId" => "012.345.678-90",
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D")),
        "description" => "take my money one more time",
        "tags" => ["again"],
    ]),
]);

foreach($payments as $payment){
    print_r($payment);
}
```

**Note**: Instead of using BoletoPayment objects, you can also pass each payment element directly in array format, without using the constructor

### Get a boleto payment

To get a single boleto payment by its id, run:

```php
use StarkBank\BoletoPayment;

$payment = BoletoPayment::get("19278361897236187236");

print_r($payment);
```

### Get a boleto payment PDF

After its creation, a boleto payment PDF may be retrieved by passing its id.

```php
use StarkBank\BoletoPayment;

$pdf = BoletoPayment::pdf("5155165527080960");

$fp = fopen('boletoPayment.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete a boleto payment

You can also cancel a boleto payment by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\BoletoPayment;

$payment = BoletoPayment::delete("5155165527080960");

print_r($payment);
```

### Query boleto payments

You can search for boleto payments using filters.

```php
use StarkBank\BoletoPayment;

$payments = BoletoPayment::query([
    "tags" => ["company_1", "company_2"]
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Query boleto payment logs

Searches are also possible with boleto payment logs:

```php
use StarkBank\BoletoPayment;

$logs = BoletoPayment\Log::query([
    "paymentIds" => ["5155165527080960", "76551659167801921"],
]);

foreach($logs as $log){
    print_r($log);
}
```

### Get a boleto payment log

You can also get a boleto payment log by specifying its id.

```php
use StarkBank\BoletoPayment;

$log = BoletoPayment\Log::get("5155165527080960");

print_r($log);
```

### Create a utility payment

It's also simple to pay utility bills (such as electricity and water bills) in the SDK.

```php
use StarkBank\UtilityPayment;

$payments = UtilityPayment::create([
    new UtilityPayment([
        "line" => "83680000001 7 08430138003 0 71070987611 8 00041351685 7",
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P2D")),
        "description" => "take my money",
        "tags" => ["take", "my", "money"],
    ]),
    new UtilityPayment([
        "barCode" => "83600000001522801380037107172881100021296561",
        "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D")),
        "description" => "take my money one more time",
        "tags" => ["again"],
    ]),
]);

foreach($payments as $payment){
    print_r($payment);
}
```

**Note**: Instead of using UtilityPayment objects, you can also pass each payment element directly in array format, without using the constructor

### Query utility payments

To search for utility payments using filters, run:

```php
use StarkBank\UtilityPayment;

$payments = UtilityPayment::query([
    "tags" => ["electricity", "gas"]
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Get a utility payment

You can get a specific bill by its id:

```php
use StarkBank\UtilityPayment;

$payment = UtilityPayment::get("5155165527080960");

print_r($payment);
```

### Get a utility payment PDF

After its creation, a utility payment PDF may also be retrieved by passing its id.

```php
use StarkBank\UtilityPayment;

$pdf = UtilityPayment::pdf("5155165527080960");

$fp = fopen('electricity.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete a utility payment

You can also cancel a utility payment by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\UtilityPayment;

$payment = UtilityPayment::delete("5155165527080960");

print_r($payment);
```

### Query utility payment logs

You can search for payments by specifying filters. Use this to understand the
bills life cycles.

```php
use StarkBank\UtilityPayment;

$logs = UtilityPayment\Log::query([
    "paymentIds" => ["102893710982379182", "92837912873981273"],
]);

foreach($logs as $log){
    print_r($log);
}
```

### Get a utility payment log

If you want to get a specific payment log by its id, just run:

```php
use StarkBank\UtilityPayment;

$log = UtilityPayment\Log::get("1902837198237992");

print_r($log);
```

### Create payment requests to be approved by authorized people in a cost center

You can also request payments that must pass through a specific cost center approval flow to be executed.
In certain structures, this allows double checks for cash-outs and also gives time to load your account
with the required amount before the payments take place.
The approvals can be granted at our website and must be performed according to the rules
specified in the cost center.

**Note**: The value of the centerId parameter can be consulted by logging into our website and going
to the desired cost center page.

```php
use StarkBank\PaymentRequest;


$requests = PaymentRequest::create([
    new PaymentRequest([
        "centerId" => "5967314465849344",
        "payment" => new Transfer([
            "amount" => 100,
            "bankCode" => "033",
            "branchCode" => "0001",
            "accountNumber" => "10000-0",
            "taxId" => "012.345.678-90",
            "name" => "Tony Stark",
            "tags" => ["iron", "suit"]
        ]),
        "due" => (new DateTime("now"))->add(new DateInterval("P30D"))
    ])
]);

foreach($requests as $request){
    print_r($request);
}
```

**Note**: Instead of using PaymentRequest objects, you can also pass each request element directly in array format, without using the constructor


### Query payment requests

To search for payment requests, run:

```php
use StarkBank\PaymentRequest;

$requests = PaymentRequest::query(["centerId" => "5967314465849344", "limit" => 10]);

foreach($requests as $request){
    print_r($request);
}
```

### Create a webhook subscription

To create a webhook subscription and be notified whenever an event occurs, run:

```php
use StarkBank\Webhook;

$webhook = Webhook::create([
    "url" => "https://webhook.site/dd784f26-1d6a-4ca6-81cb-fda0267761ec",
    "subscriptions" => ["transfer", "invoice", "deposit", "brcode-payment", "boleto", "boleto-payment", "utility-payment"]
]);

print_r($webhook);
```

### Query webhook subscriptions

To search for registered webhooks, run:

```php
use StarkBank\Webhook;

$webhooks = Webhook::query();

foreach($webhooks as $webhook){
    print_r($webhook);
}
```

### Get a webhook subscription

You can get a specific webhook by its id.

```php
use StarkBank\Webhook;

$webhook = Webhook::get("10827361982368179");

print_r($webhook);
```

### Delete a webhook subscription

You can also delete a specific webhook by its id.

```php
use StarkBank\Webhook;

$webhook = Webhook::delete("10827361982368179");

print_r($webhook);
```

### Process webhook events

It's easy to process events that arrived in your webhook. Remember to pass the
signature header so the SDK can make sure it's really StarkBank that sent you
the event.

```php
use StarkBank\Event;

$response = listen()  # this is the method you made to get the events posted to your webhook

$event = Event::parse($response->content, $response->headers["Digital-Signature"]);

if ($event->subscription == "transfer"){
    print_r($event->log->transfer);
} elseif ($event->subscription == "deposit"){
    print_r($event->log->deposit);
} elseif ($event->subscription == "invoice"){
    print_r($event->log->invoice);
} elseif ($event->subscription == "brcode-payment"){
    print_r($event->log->payment);
} elseif ($event->subscription == "boleto"){
    print_r($event->log->boleto);
} elseif ($event->subscription == "boleto-payment"){
    print_r($event->log->payment);
} elseif ($event->subscription == "utility-payment"){
    print_r($event->log->payment);
}
```

### Query webhook events

To search for webhooks events, run:

```php
use StarkBank\Event;

$events = Event::query(["after" => "2020-03-20", "isDelivered" => false]);

foreach($events as $event){
    print_r($event);
}
```

### Get a webhook event

You can get a specific webhook event by its id.

```php
use StarkBank\Event;

$event = Event::get("10827361982368179");

print_r($event);
```

### Delete a webhook event

You can also delete a specific webhook event by its id.

```php
use StarkBank\Event;

$event = Event::delete("10827361982368179");

print_r($event);
```

### Set webhook events as delivered

This can be used in case you've lost events.
With this function, you can manually set events retrieved from the API as
"delivered" to help future event queries with `isDelivered=false`.

```php
use StarkBank\Event;

$event = Event::update("129837198237192", ["isDelivered" => true]);

print_r($event);
```

### Get a DICT key

You can get Pix key's parameters by its id.

```php
use StarkBank\DictKey;

$dictKey = DictKey::get();

print_r($dictKey);
```

### Query your DICT keys

To take a look at the Pix keys linked to your workspace, just run the following:

```php
use StarkBank\DictKey;

$dictKeys = iterator_to_array(DictKey::query(["limit" => 1, "type" => "evp", "status" => "registered"]));

foreach($dictKeys as $dictKey) {
    print_r($dictKey);
}
```

### Create a new Workspace

The Organization user allows you to create new Workspaces (bank accounts) under your organization.
Workspaces have independent balances, statements, operations and users.
The only link between your Workspaces is the Organization that controls them.

**Note**: This route will only work if the Organization user is used with `workspaceId=null`.

```php
use StarkBank\Workspace;

$workspace = Workspace::create([
    "username" => "iron-bank-workspace-1",
    "name" => "Iron Bank Workspace 1"
    ],
    $organization
);

print_r($workspace)
```

### List your Workspaces

This route lists Workspaces. If no parameter is passed, all the workspaces the user has access to will be listed, but
you can also find other Workspaces by searching for their usernames or IDs directly.

```php
use StarkBank\Workspace;

$workspaces = Workspace::query(["limit" => 30]);

foreach($workspaces as $workspace){
    print_r($workspace);
}
```

### Get a Workspace

You can get a specific Workspace by its id.

```php
use StarkBank\Workspace;

$workspace = Workspace::get("10827361982368179");

print_r($workspace);
```


## Handling errors

The SDK may raise one of four types of errors: __InputErrors__, __InternalServerError__, __UnknownError__, __InvalidSignatureError__

__InputErrors__ will be raised whenever the API detects an error in your request (status code 400).
If you catch such an error, you can get its elements to verify each of the
individual errors that were detected in your request by the API.
For example:

```php
use StarkBank\Transaction;
use StarkBank\Error\InputErrors;

try {
    $transactions = Transaction::create([
        new Transaction([
            "amount" => 99999999999999,  # (R$ 999,999,999,999.99)
            "receiverId" => "1029378109327810",
            "description" => ".",
            "externalId" => "12345",  # so we can block anything you send twice by mistake
            "tags" => ["provider"]
        ]),
    ]);
} catch (InputErrors $e) {
    foreach($e->errors as $error){
        echo "\n\ncode: " . $error->errorCode;
        echo "\nmessage: " . $error->errorMessage;
    }
}
```

__InternalServerError__ will be raised if the API runs into an internal error.
If you ever stumble upon this one, rest assured that the development team
is already rushing in to fix the mistake and get you back up to speed.

__UnknownError__ will be raised if a request encounters an error that is
neither __InputErrors__ nor an __InternalServerError__, such as connectivity problems.

__InvalidSignatureError__ will be raised specifically by StarkBank\Event::parse()
when the provided content and signature do not check out with the Stark Bank public
key.
