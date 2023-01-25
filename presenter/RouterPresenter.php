<?php

class RouterPresenter extends Presenter
{
    /**
     * Connects wanted action with view
     * @var Presenter chosen depending on current URL
     */
    protected Presenter $presenter;

    public function process(array $params) : void
    {
        $parsedURL = $this->parseURL($params[0]);

        // if no params in URL are given do whatever you want
        // in this case I redirected to landing-page
        // you can also set view instead of redirecting
        if (empty($parsedURL[0]))
            $this->redirect('landing-page');

        $presenterClass = $this->dashesToCamel(
            array_shift($parsedURL[0])
        ) . "Presenter";

        if (!file_exists('presenter/' . $presenterClass . '.php'))
            $this->redirect('error');
        
        $this->presenter = new $presenterClass;
        $this->presenter->process($parsedURL);

        $this->data['title'] = $this->presenter->header['title'];
        $this->data['description'] = $this->presenter->header['description'];
        $this->data['keywords'] = $this->presenter->header['keywords'];

        // Layout view contains the skeleton of the page (header,...)
        // You can also create navbar here and have it on every page
        $this->view = 'layout';
    }

    /**
     * Parses URL from string to array
     * @param string $url to be parsed
     * @return array of URL subpaths
     */
    private function parseURL(string $url) : array
    {
        return explode("/", trim(ltrim(parse_url($url)['path'], "/")));
    }

    /**
     * Transforms text from dashes to camel notation
     * @param string $txt string to be transformed
     * @return string transformed text
     */
    private function dashesToCamel(string $txt) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $txt)));
    }
}