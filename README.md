# Validaide HtmlBuilder

## Introduction
... is a small library with a fluent interface to generate snippets of HTML code.
Why in God's name would you need such a thing!? We have, I don't know, awesome stuff like [Twig](https://twig.symfony.com/), right!?
And you are right! I :heart: Twig! But in turns out our code base still finds itself with small helper methods that generate tiny snippets of HTML.

Take the example below:

```php
public function userStateToIcon(User $user)
{
  switch ($user->getState()->value()) {
    case UserState::PENDING: {
      $class = 'pending';
      break;
    }
    case UserState::ACTIVE: {
      $class = 'active';
      break;
    }
    case UserState::SUSPENDED: {
      $class = 'suspended';
      break;
    }
    default: {
      throw new \LogicException(sprintf("Unsupported User Type: %s", $user->getState()->value()));
    }
  }
  
  return sprintf('<span class="%s" id="%s" data-username="%s"></span>', $class, $user->getId(), $user->getUsername()):
}
```

Now, despite that the above can be optimized still, the last return statement is the one we are trying to simplify:

```php
HTML::create('span')
  ->class($class)
  ->id($user->getId())
  ->attr('data-username',$user->getUsername());
```

Now, in number of characters written, it is not necessarily faster, but it will ensure:
1. Valid HTML is generated
2. Safe HTML is generated
3. It is easier to modify the 'HTML' being built, afterwards

## Installation

Our big friend [Composer](https://getcomposer.org/) to the rescue:
```
composer install validaide/html-builder
```

## Examples
1. Plain tag:
   ```php
   HTML::create('span')
   ```
   ```html
   <span></span>
   ```
2. Plain tag with content:
   ```php
   HTML::create('h1')->text('Heading 1');
   ```
   ```html
   <h1>Heading 1</h1>
   ```
3. Nested tags:
   ```php
   HTML::create('div')->id('div-1)->tag('div')->id('div-2);
   ```
   ```html
   <div id="div-1"><div id="div-2"></div></div>
   ```