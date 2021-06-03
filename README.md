# Contao Privacy Bundle

This bundle contains functionality concerning privacy and the European Union's "General Data Protection Regulation" (GDPR, in German: "Datenschutz-Grundverordnung", DSGVO).

## Legal disclaimer

Use this bundle at your own risk. Although we as the developer try our best to design this bundle to fulfill the legal requirements we __CAN'T GUARANTEE__ anything in terms completeness and correctness. Also we don't offer any legal consulting. We strongly encourage you to consult a lawyer if you have any questions or concerns.

## Features

- privacy protocol
    - adds the new Contao entities `tl_privacy_protocol_archive` and `tl_privacy_protocol_entry` for storing privacy relevant actions like opt-ins, ...
    - offers a simply API for adding new entries into the privacy protocol
    - offers functionality to create new privacy protocol entries for `tl_member` callbacks (`oncreate_callback`, `onversion_callback`, `ondelete_callback`)
- opt-in/out form for the frontend with connection to privacy protocol
- backend opt in email module for "inviting" users to opt into a privacy relevant agreement

## Installation

1. Simply install using composer: `composer require heimrichhannot/contao-privacy-bundle`
2. Update your database and clear your caches.
3. Now you have the new menu entry "privacy" in the Contao menu on the left

## Export the privacy protocol

Export entries from the privacy protocol as `csv` or `excel` is already possible. In order to avail this feature, simply install one of the following composer modules:

`composer require heimrichhannot/contao-exporter-bundle`

## Usage

### The privacy protocol

1. Add a new protocol archive and select the fields you'd like to store (CAUTION: Do NOT store personal data for which you don't have the user's permission!).
2. Choose one of the following functions for adding new entries programmatically and/or create entries after creating, updating or deleting members automatically.

#### Create entries on `tl_member` CRUD actions

You can activate the automated creation of privacy protocol entries for the following `tl_member` callbacks:

- `oncreate_callback`
- `onversion_callback` (this represents updating a member where at least one attribute has actually been changed)
- `ondelete_callback`

Just open contao's global settings (`tl_settings`) and configure to your needs in the "privacy" section.

#### Create entries programmatically

##### Add a new entry from the context of a module

```php
// this represents your function for sending the opt in email
$success = $this->sendOptInEmail($firstname, $lastname, $email);

// only create a protocol entry if the email has indeed been sent
if ($success)
{
    System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntryFromModule(
        // the type of action
        \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_IN,
        // the id of your destination protocol archive
        1,
        // the data you want to add to the protocol entry to be created
        // CAUTION: Do NOT store personal data for which you don't have the user's permission!
        [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email
        ],
        // the \Contao\Module instance you're calling from
        $this,
        // optional: composer package name of the bundle your module lives in (version is retrieved automatically from composer.lock)
        'acme/contao-my-bundle'
    );
}
```

##### Add a new entry from the context of a content element

```php
// this represents your function for sending the opt in email
$success = $this->sendOptInEmail($firstname, $lastname, $email);

// only create a protocol entry if the email has indeed been sent
if ($success)
{
    System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntryFromContentElement(
        // the type of action
        \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_IN,
        // the id of your destination protocol archive
        1,
        // the data you want to add to the protocol entry to be created
        // CAUTION: Do NOT store personal data for which you don't have the user's permission!
        [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email
        ],
        // the \Contao\ContentElement instance you're calling from
        $this,
        // optional: composer package name of the bundle your content element lives in (version is retrieved automatically from composer.lock)
        'acme/contao-my-bundle'
    );
}
```

##### Add a new entry from a general context

```php
// this represents your function for sending the opt in email
$success = $this->sendOptInEmail($firstname, $lastname, $email);

// only create a protocol entry if the email has indeed been sent
if ($success)
{
    System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntry(
        // the type of action
        \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_IN,
        // the id of your destination protocol archive
        1,
        // the data you want to add to the protocol entry to be created
        // CAUTION: Do NOT store personal data for which you don't have the user's permission!
        [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email
        ],
        // optional: composer package name of the bundle your code lives in (version is retrieved automatically from composer.lock)
        'acme/contao-my-bundle'
    );
}
```

### The Protocol Entry Editor

The module `ModuleProtocolEntryEditor` can be used to create entries in the privacy protocol. Typically you have the following scenarios:

#### Do a double opt-in for getting an agreement for some action (e.g. send advertising emails)

In this scenario you can:

- show a form with fields of a DCA specified by you (typically this would be `tl_member`) -> here the user can type in his data *(ATTENTION: Only ask for fields that are necessary for the action you're requesting the opt-in here)*
- this form can be prefilled using a prepared URL that can be generated by the insert tag `privacy_opt_url` (see InsertTag chapter)
- you can also avoid showing any fields by not selected any in the module configuration
- after clicking "submit" in the form, the user typically gets an email (using contao notification center) for confirming his agreement (you can skip the confirmation only if that's legally ok in your country)
- you can specify which protocol entries should generated after submit and after confirm

#### Do a single opt-out for revoking the agreement for some action (e.g. send advertising emails)

You can do that as you would do a single

### The Backend Opt-In Form

Navigate to "opt-in" in the privacy section on the left for sending an opt-in email to a certain email address.
The form works as follows:

1. The information you type into the form is converted into an encrypted JWT token and appended to a prepared link (created with the insert tag `privacy_opt_url`)
   in a notification email.
2. After the user clicks the link, he is redirected to a page containing the module `ModuleProtocolEntryEditor`. This module recognizes the JWT parameter and uses it to prefill the form for the user so that he only has to click "Submit".

* HINT: The data you type into the backend opt-in form isn't directly stored into your database. The only case this happens is in an encrypted way as a JWT token in the notification queue of contao notification center. So use a module like `heimrichhannot/contao-cleaner-bundle` to periodically delete this token data. *

### Insert Tags

Name | Arguments | Example
---- | --------- | -------
privacy_opt_url | 1. The data for prefilling the form of `ModuleProtocolEntryEditor` and for the resulting privacy protocol entry in the format: `fieldName1:fieldValue1#fieldName2:fieldValue2`<br>2. The jumpTo page id<br>3. The data for finding a corresponding database entity to be linked to the protocol entry (reference table and field is defined in the protocol entry editor's backend config): `referenceFieldValue` (optional) | `{{privacy_opt_url::email:john@example.org#firstname:John#lastname:Doe::1::john@example.org}}`
