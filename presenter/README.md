# Presenters

This directory contains presenters. I already contained ```Presenter.php``` from which all presenters should inherit and I also contained ```RouterPresenter.php``` which I will
describe later.

## What is presenter?

Presenters connnect Models and Views. Presenter is called based on URL and then it uses
Models to get data from database or do some logic and passes resulted data to the view.
Presenter should not contain any logic.

## RouterPresenter.php

**RouterPresenter** parses URL that is received so every subpath is an list item. Based on
the first subpath it calls corresponding Presenter to deal with it. If you want to have
URL ```/github-example```, your Presenter should be called ```GithubExamplePresenter.php```
so it can be automatically be called. As you can tell, it transforms URL in dashes notation
to camel notation.  


Example of inheriting Presenter:
```php
<?php
class ExamplePresenter extends Presenter
{
    public function process(array $params) : void
    {
        // There you will do whatever you want
    }
}
```