Authorize Action
================

Add functionality for authorize action before executing.

Requirements
------------

* PHP 7.1 or higher

Installation
------------

Add AuthorizeAction package in your composer.json:

````json
{
    "require": {
        "fivelab/authorize-action": "~1.0"
    }
}
````

Now tell composer to download the library by running the command:

```bash
$ php composer.phar update fivelab/authorize-action
```

Why?
----

In many cases, you should check grants before executing command/code. This library add functionality for
easy declare the authorize action and verify the action before executing. 

#### Examples:

For start, you should declare the authorize action. The action should implement 
`FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface`:

```php
<?php

namespace Application\Security;

use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;

/**
 * The authorize action for check grants for edit post
 */
class EditPostAction implements AuthorizeActionInterface
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * Constructor.
     * 
     * @param int $postId 
     */
    public function __construct(int $postId) 
    {
        $this->id = $postId;        
    }
}

```

Secondary, you should declare the verifier for verifying this action. The verifier should implement
`FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierInterface`:

```php
<?php

namespace Application\Security\Verifier;

use Application\Security\EditPostAction;
use FiveLab\Component\AuthorizeAction\Action\AuthorizeActionInterface;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EditPostVerifier implements AuthorizeActionVerifierInterface
{
    /**
     * {@inheritdoc} 
     */
    public function supports(AuthorizeActionInterface $action, UserInterface $user): bool 
    {
        return $action instanceof EditPostAction;
    }
    
    /**
     * {@inheritdoc} 
     */
    public function verify(AuthorizeActionInterface $action, UserInterface $user): void 
    {
        if (!$user->isSuperAdmin() && !$user->isCopywriter()) {
            throw new AccessDeniedException();
        }
    }
}

```

> **Attention:** the verifier should throw `AccessDeniedException` if the action not verified.

In last step you should create the authorization checker:

```php
<?php

use Application\Security\Verifier\EditPostVerifier;
use FiveLab\Component\AuthorizeAction\AuthorizationChecker;
use FiveLab\Component\AuthorizeAction\Verifier\AuthorizeActionVerifierChain;
use FiveLab\Component\AuthorizeAction\UserProvider\SymfonyTokenStorageUserProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

$tokenStorage = new TokenStorage();
$userProvider = new SymfonyTokenStorageUserProvider($tokenStorage);

$verifierChain = new AuthorizeActionVerifierChain();
$verifierChain->add(new EditPostVerifier());

$authorizationChecker = new AuthorizationChecker($verifierChain, $userProvider);

```

Great! After creating the checker you can check right for executing action:

```php
$authorizationChecker->verify(new EditPostAction($postId));
```  

> **Attention:** If the action not verified (not granted) the authorization check throws `AccessDeniedException`.


License
-------

This library is under the MIT license. See the complete license in library

```
LICENSE
```

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/FiveLab/AuthorizeAction/issues).

Contributors:
-------------

Thanks to [everyone participating](https://github.com/FiveLab/AuthorizeAction/graphs/contributors) in the development of this AuthorizeAction library!

