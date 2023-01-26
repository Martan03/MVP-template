# Presenters

This directory contains presenters. I already contained ```Presenter.php``` from which all presenters should inherit.

## What is presenter?

Presenters connnect Models and Views. Presenter is called based on URL and then it uses Models to get data from database or  
do some logic and passes resulted data to the view. Presenter should not contain any logic.

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