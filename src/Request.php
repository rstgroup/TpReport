<?php

namespace TpReport;

/**
 * Fetching reports ( or collections of entities using TargetProcess's API
 * 
 * @author Marek Ziółkowski
 */
class Request {
    
    protected $params = array();
    protected $collection;
    private $api_base_url;
    private $username;
    private $password;
    
    public function __construct($api_base_url, $username = null, $password = null) {
        if ($api_base_url =='') {
            throw new Exception('Undefined API base URL in config');
        } else {
            $this->api_base_url = $api_base_url;
        }
        $this->username = $username;
        $this->password = $password;
    }
    
    /**
     * Generates the condition string that will be put in the WHERE part.
     * Based on Yii query builder.
     * 
     * @return string the condition string to put in the WHERE part
     * @throws Exception
     */
    private function processConditions($conditions) {
        if (!is_array($conditions)) {
            return $conditions;        
        } elseif($conditions===array()) {
            return '';
        }
        $n = count($conditions);
        $operator = strtolower($conditions[0]);
        if ($operator === 'or' || $operator === 'and') {
            $parts = array();
            for ($i = 1; $i < $n;  ++$i) {
                $condition = $this->processConditions($conditions[$i]);
                if ($condition !== '') {
                    $parts[] = '(' . $condition . ')';
                }
            }
            return $parts === array() ? '' : implode(' ' . $operator . ' ', $parts);
        }
        
         if (!isset($conditions[1], $conditions[2])) {
            return '';
        }

        $column = $conditions[1];
        $values = $conditions[2];
        if (!is_array($values)) {
            $values = array($values);
        }

        if ($operator === 'in' || $operator === 'not in') {
            if ($values === array()) {
                return $operator === 'in' ? '0=1' : '';
            } foreach ($values as $i => $value) {
                if (is_string($value)) {
                    $values[$i] = "'".$value."'";
                }
                else {
                    $values[$i] = (string) $value;
                }
            }
            return $column . ' ' . $operator . ' (' . implode(',', $values) . ')';
        }
        throw new \Exception('Unknown operator '.$operator);
    }
    
    /**
     * Set collection name.
     * 
     * @param string $name Collection name
     */
    public function collection($name) {
        $this->collection = $name;
        return $this;
    }
    
    /**
     * Always overwrites previews where conditions.
     * 
     * @param mixed $conditions the conditions that should be put in the WHERE part.
     * @return \TpReport
     */
    public function where($conditions) {
        $this->params['where'] = $this->processConditions($conditions);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param array $values
     */
    private function addArrayParams($name, $values) {
        if (!is_array($values)) {
            $values = array($values);
        }
        
        if ($name != '') {
            $this->params[$name] = $values;
        }
    }
    
    /**
     * You can get entities with specified fields only.
     * 
     * @param array $fields Fields to be included
     * @return \TpReport
     */
    public function inc($fields) {
        $this->addArrayParams('include', $fields);
        return $this;
    }

    /**
     * Set the amount of items to be taken.
     * @see http://dev.targetprocess.com/rest/response_format#paging
     * 
     * @param int $limit
     * @return \TpReport
     */
    public function take($limit) {
        if (is_int($limit)) {
            $this->params['take'] = $limit;
        }
        return $this;
    }
    
    /**
     * Final URL buliding.
     * @see http://dev.targetprocess.com/rest/response_format#filtering
     * 
     * @return string
     */
    public function getUrl()
    {
        if (!isset($this->collection) || $this->collection == '') {
            throw new \Exception("Can't build URL: undefined collection");
        }
        $q = $this->api_base_url . '/' . $this->collection;
        if (!empty($this->params)) {
            $first = true;
            foreach ($this->params as $name => $value) {
                if (is_array($value)) {
                    $value_arr = $value;
                    $value = '[' . implode(',', $value_arr) . ']';
                }
                if ($value != '') {            
                    $q.=($first ? '?' : '&') . $name . '=' . $value;
                    $first = false;
                }
            }
        }
        return $q;
    }

    /**
     * Do the request.
     * 
     * @return array 
     * @throws TpReport\HttpErrorException
     */
    public function query() 
    {    
        $response = \Httpful\Request::get(str_replace(" ", "%20", $this->getUrl()))
                ->addHeader('Accept', 'application/json')
                ->authenticateWith($this->username, $this->password)
                ->send();
        $error_code = (int)$response->code;
        
        if ((int)$error_code != 200) {
            throw new \TpReport\HttpErrorException($error_code);
        }

        return $response->body;
    }
}


