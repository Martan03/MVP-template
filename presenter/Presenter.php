<?php

/**
 * Presenter abstract class
 * Implements variables and basic methods
 */
abstract class Presenter
{
    /**
     * @var array data that will be passed to the view
     */
    protected array $data = array();
    /**
     * @var string name of the view to be displayed
     */
    protected string $view = "";
    /**
     * @var array page header info (title, description, keywords)
     */
    protected array $header = array(
        'title' => '',
        'description' => '',
        'keywords' => ''
    );

    /**
     * Processes given URL in array
     * This function will be implemented in each presenter
     * @param array $params URL array
     */
    abstract function process(array $params) : void;

    /**
     * Writes view and extracts validated data
     * To access non-validated data use '_' before variable name
     */
    public function writeView() : void
    {
        if (!$this->view)
            return;

        extract($this->validate($this->data));
        extract($this->data, EXTR_PREFIX_ALL, "");
        require("template/" . $this->view . ".phtml");
    }

    /**
     * Redirects to given URL and closes connection
     * @param string $url to be redirected to
     */
    public function redirect(string $url) : never
    {
        header('Location: /' . $url);
        header('Connection: close');
        exit;
    }

    /**
     * Validates given variable using htmlspecialchars
     * @param $x variable to be validated
     * @return mixed validated variable
     */
    public function validate($x = null) : mixed
    {
        if (!isset($x))
            return null;
        if (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        if (is_array($x))
            foreach ($x as $key => $value)
                $x[$key] = $this->validate($value);

        return $x;
    }
}