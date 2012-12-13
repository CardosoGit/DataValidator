<?php
/**
 * Class for validation of data
 *
 * @author Rafael Wendel Pinheiro (http://www.rafaelwendel.com)
 * @version 1.0
 * @link https://github.com/rafaelwendel/DataValidator/
 */
class Data_Validator {
    
    protected $_data     = array();
    protected $_errors   = array();
    protected $_pattern  = array();
    protected $_messages = array();
    
    /**
     * Construct method (Set the error messages default)
     * @access public
     * @return void
     */
    public function __construct() {
        $this->set_messages_default();
    }
    
    
    /**
     * Set a data for validate
     * @access public
     * @param $name String The name of data
     * @param $value Mixed The value of data
     * @return Data_Validator The self instance
     */
    public function set($name, $value){
        $this->_data['name'] = $name;
        $this->_data['value'] = $value;
        return $this;
    }
    
    
    /**
     * Set error messages default born in the class
     * @access protected
     * @return void
     */
    protected function set_messages_default(){
        $this->_messages = array(
            'is_required'    => 'O campo %s é obrigatório',
            'min_length'     => 'O campo %s deve conter ao mínimo %s caracter(es)',
            'max_length'     => 'O campo %s deve conter ao máximo %s caracter(es)',
            'between_length' => 'O campo %s deve conter entre %s e %s caracter(es)',
            'min_value'      => 'O valor do campo %s deve ser maior que %s ',
            'max_value'      => 'O valor do campo %s deve ser menor que %s ',
            'between_values' => 'O valor do campo %s deve estar entre %s e %s',
            'is_email'       => 'O email %s não é válido ',
            'is_url'         => 'A URL %s não é válida ',
            'is_slug'        => '%s não é um slug ',
            'is_num'         => 'O valor %s não é numérico ',
            'is_integer'     => 'O valor %s não é inteiro ',
            'is_float'       => 'O valor %s não é float ',
            'is_string'      => 'O valor %s não é String ',
            'is_boolean'     => 'O valor %s não é booleano ',
            'is_obj'         => 'A variável %s não é um objeto ',
            'is_arr'         => 'A variável %s não é um array ',
            'is_equals'      => 'O valor do campo %s deve ser igual à %s ',
            'is_not_equals'  => 'O valor do campo %s não deve ser igual à %s ',
            'is_cpf'         => 'O valor %s não é um CPF válido ',
            'is_cnpj'        => 'O valor %s não é um CNPJ válido ',
            'contains'       => 'O campo %s só aceita um do(s) seguinte(s) valore(s): [%s] ',
            'not_contains'   => 'O campo %s não aceita o(s) seguinte(s) valore(s): [%s] ',
            'is_lowercase'   => 'O campo %s só aceita caracteres minúsculos ',
            'is_uppercase'   => 'O campo %s só aceita caracteres maiúsculos ',
            'is_multiple'    => 'O valor %s não é múltiplo de %s'
        );
    }
    
    
    /**
     * The number of validators methods available in DataValidator
     * @access public
     * @return int Number of validators methods
     */
    public function get_number_validators_methods(){
        return count($this->_messages);
    }
    
    /**
     * Define a custom error message for some method
     * @access public
     * @param String $name The name of the method
     * @param String $value The custom message
     * @return void
     */
    public function set_message($name, $value){
        if (array_key_exists($name, $this->_messages)){
            $this->_messages[$name] = $value;
        }
    }
    
    
    /**
     * Get the error messages
     * @access public
     * @param String $param [optional] A specific method
     * @return Mixed One array with all error messages or a message of specific method
     */
    public function get_messages($param = false){
        if ($param){
            return $this->_messages[$param];
        }
        return $this->_messages;
    }
    
    
    /**
     * Define the pattern of name of error messages
     * @access public
     * @param String $prefix [optional] The prefix of name
     * @param String $sufix [optional] The sufix of name
     * @return void
     */
    public function define_pattern($prefix = '', $sufix = ''){
        $this->_pattern['prefix'] = $prefix;
        $this->_pattern['sufix']  = $sufix;
    }
    
    
    /**
     * Set a error of the invalid data
     * @access protected
     * @param String $error The error message
     * @return void
     */
    protected function set_error($error){
        $this->_errors[$this->_pattern['prefix'] . $this->_data['name'] . $this->_pattern['sufix']][] = $error;
    }
        
    /**
     * Verify if the current data is not null
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_required(){
        if (empty ($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_required'], $this->_data['name']));
        }
        return $this;
    } 
    
    
    /**
     * Verify if the length of current value is less than the parameter
     * @access public
     * @param Int $length The value for compare
     * @param Boolean $inclusive [optional] Include the lenght in the comparison
     * @return Data_Validator The self instance
     */
    public function min_length($length, $inclusive = false){
        $verify = ($inclusive === true ? strlen($this->_data['value']) >= $length : strlen($this->_data['value']) > $length);
        if (!$verify){
            $this->set_error(sprintf($this->_messages['min_length'], $this->_data['name'], $length));
        }
        return $this;
    }
    
    
    /**
     * Verify if the length of current value is more than the parameter
     * @access public
     * @param Int $length The value for compare
     * @param Boolean $inclusive [optional] Include the lenght in the comparison
     * @return Data_Validator The self instance
     */
    public function max_length($length, $inclusive = false){
        $verify = ($inclusive === true ? strlen($this->_data['value']) <= $length : strlen($this->_data['value']) < $length);
        if (!$verify){
            $this->set_error(sprintf($this->_messages['max_length'], $this->_data['name'], $length));
        }
        return $this;
    }
    
    
    /**
     * Verify if the length current value is between than the parameters
     * @access public
     * @param Int $min The minimum value for compare
     * @param Int $max The maximum value for compare
     * @return Data_Validator The self instance
     */
    public function between_length($min, $max){
        if(strlen($this->_data['value']) < $min || strlen($this->_data['value']) > $max){
            $this->set_error(sprintf($this->_messages['between_length'], $this->_data['name'], $min, $max));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current value is less than the parameter
     * @access public
     * @param Int $value The value for compare
     * @param Boolean $inclusive [optional] Include the value in the comparison
     * @return Data_Validator The self instance
     */
    public function min_value($value, $inclusive = false){
        $verify = ($inclusive === true ? !is_numeric($this->_data['value']) || $this->_data['value'] >= $value : !is_numeric($this->_data['value']) || $this->_data['value'] > $value);
        if (!$verify){
            $this->set_error(sprintf($this->_messages['min_value'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current value is more than the parameter
     * @access public
     * @param Int $value The value for compare
     * @param Boolean $inclusive [optional] Include the value in the comparison
     * @return Data_Validator The self instance
     */
    public function max_value($value, $inclusive = false){
        $verify = ($inclusive === true ? !is_numeric($this->_data['value']) || $this->_data['value'] <= $value : !is_numeric($this->_data['value']) || $this->_data['value'] < $value);
        if (!$verify){
            $this->set_error(sprintf($this->_messages['max_value'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    
    /**
     * Verify if the length of current value is more than the parameter
     * @access public
     * @param Int $min_value The minimum value for compare
     * @param Int $max_value The maximum value for compare
     * @return Data_Validator The self instance
     */
    public function between_values($min_value, $max_value){
        if(!is_numeric($this->_data['value']) || (($this->_data['value'] < $min_value || $this->_data['value'] > $max_value ))){
            $this->set_error(sprintf($this->_messages['between_values'], $this->_data['name'], $min_value, $max_value));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a valid email
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_email(){
        if (filter_var($this->_data['value'], FILTER_VALIDATE_EMAIL) === false) {
            $this->set_error(sprintf($this->_messages['is_email'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a valid URL
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_url(){
        if (filter_var($this->_data['value'], FILTER_VALIDATE_URL) === false) {
            $this->set_error(sprintf($this->_messages['is_url'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a slug
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_slug(){
        $verify = true;
        
        if (strstr($input, '--')) {
            $verify = false;
        }
        if (!preg_match('@^[0-9a-z\-]+$@', $input)) {
            $verify = false;
        }
        if (preg_match('@^-|-$@', $input)){
            $verify = false;
        }        
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_url'], $this->_data['value']));
        }
        return $this;        
    }
    
    
    /**
     * Verify if the current data is a numeric value
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_num(){
        if (!is_numeric($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_num'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a integer value
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_integer(){
        if (!is_numeric($this->_data['value']) && (int) $this->_data['value'] != $this->_data['value']){
            $this->set_error(sprintf($this->_messages['is_integer'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a float value
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_float(){
        if (!is_float(filter_var($this->_data['value'], FILTER_VALIDATE_FLOAT))){
            $this->set_error(sprintf($this->_messages['is_float'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a string value
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_string(){
        if(!is_string($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_string'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a boolean value
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_boolean(){
        if(!is_bool($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_boolean'], $this->_data['value']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a object
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_obj(){
        if(!is_object($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_obj'], $this->_data['name']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a array
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_arr(){
        if(!is_array($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_arr'], $this->_data['name']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is equals than the parameter
     * @access public
     * @param String $value The value for compare
     * @param Boolean $identical [optional] Identical?
     * @return Data_Validator The self instance
     */
    public function is_equals($value, $identical = false){
        $verify = ($identical === true ? $this->_data['value'] === $value : strtolower($this->_data['value']) == strtolower($value));
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_equals'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is not equals than the parameter
     * @access public
     * @param String $value The value for compare
     * @param Boolean $identical [optional] Identical?
     * @return Data_Validator The self instance
     */
    public function is_not_equals($value, $identical = false){
        $verify = ($identical === true ? $this->_data['value'] !== $value : strtolower($this->_data['value']) != strtolower($value));
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_not_equals'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is a valid CPF
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_cpf(){
        $verify = true;
        
        $c = preg_replace('/\D/', '', $this->_data['value']);
        
        if (strlen($c) != 11) 
            $verify = false;

        if (preg_match("/^{$c[0]}{11}$/", $c)) 
            $verify = false;
        
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        
        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;
        
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_cpf'], $this->_data['value']));
        }
        
        return $this;
    }
    
    
    /**
     * Verify if the current data is a valid CNPJ
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_cnpj(){
        $verify = true;
        
        $c = preg_replace('/\D/', '', $input);
        $b = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
        
        if (strlen($c) != 14) 
            $verify = false;
        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);
        
        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;
        
        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);
        
        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;
        
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_cnpj'], $this->_data['value']));
        }
        
        return $this;
    }
    
    
    /**
     * Verify if the current data contains in the parameter
     * @access public
     * @param Mixed $values One array or String with valids values
     * @param String $separator If $values as a String, pass the separator of values (ex: , - | )
     * @return Data_Validator The self instance
     */
    public function contains($values, $separator = false){
        if(!is_array($values)){
            if(!$separator || is_null($values)){
                $values = array();
            }
            else{
                $values = explode($separator, $values);
            }            
        }
        
        if(!in_array($this->_data['value'], $values)){
            $this->set_error(sprintf($this->_messages['contains'], $this->_data['name'], implode(', ', $values)));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data not contains in the parameter
     * @access public
     * @param Mixed $values One array or String with valids values
     * @param String $separator If $values as a String, pass the separator of values (ex: , - | )
     * @return Data_Validator The self instance
     */
    public function not_contains($values, $separator = false){
        if(!is_array($values)){
            if(!$separator || is_null($values)){
                $values = array();
            }
            else{
                $values = explode($separator, $values);
            }            
        }
        
        if(in_array($this->_data['value'], $values)){
            $this->set_error(sprintf($this->_messages['not_contains'], $this->_data['name'], implode(', ', $values)));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is loweracase
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_lowercase(){
        if($this->_data['value'] !== mb_strtolower($this->_data['value'], mb_detect_encoding($this->_data['value']))){
            $this->set_error(sprintf($this->_messages['is_lowercase'], $this->_data['name']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is uppercase
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_uppercase(){
        if($this->_data['value'] !== mb_strtoupper($this->_data['value'], mb_detect_encoding($this->_data['value']))){
            $this->set_error(sprintf($this->_messages['is_uppercase'], $this->_data['name']));
        }
        return $this;
    }
    
    
    /**
     * Verify if the current data is multiple of the parameter
     * @access public
     * @return Data_Validator The self instance
     */
    public function is_multiple($value){
        if($value == 0){
            $verify = ($this->_data['value'] == 0);
        }
        else{
            $verify = ($this->_data['value'] % $value == 0);
        }
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_multiple'], $this->_data['value'], $value));
        }
        return $this;
    }
    
    
    /**
     * Validate the data
     * @access public
     * @return bool The validation of data 
     */
    public function validate(){
        return (count($this->_errors) > 0 ? false : true);
    }
    
    
    /**
     * The messages of invalid data
     * @param String $param [optional] A specific error
     * @return Mixed One array with messages or a message of specific error 
     */
    public function get_errors($param = false){
        if ($param){
            if(isset($this->_errors[$this->_pattern['prefix'] . $param . $this->_pattern['sufix']])){
                return $this->_errors[$this->_pattern['prefix'] . $param . $this->_pattern['sufix']];
            }
            else{
                return false;
            }
        }        
        return $this->_errors;
    }
}