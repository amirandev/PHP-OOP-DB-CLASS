<?php
require 'vendor/autoload.php';
class DB extends OOPMagic
{
    // Properties
    public static $table;
    public static $query = [];
    public static $where = [];
    public static $data = [];

    // Methods
    public static function from($table_name, $smth = null)
    {
        self::$table = $table_name;
        return new self;
    }
    
    public static function where($colmun_name, $equals_to)
    {
        self::$where[] = [$colmun_name, $equals_to];
        return new self;
    }
    
    public static function setupQuery()
    {        
        $keep = null;
        foreach(self::$where as $row){
            $valbytype = is_numeric($row[1]) ? $row[1] : "'".$row[1]."'";
            $keep .= ' AND '.$row[0].' = '.$valbytype;
        }
        $keep = ltrim($keep, 'AND ');
        
        return [
            'results' => trim('SELECT * FROM '.self::$table.' '.$keep),
            'first' => trim('SELECT * FROM '.self::$table.' '.$keep.' LIMIT 1'),
            'count' => trim('SELECT count(*) FROM '.self::$table.' '.$keep)
        ];
    }
    
    public static function get()
    {   
        self::$query = self::setupQuery();
        $magic_object = new OOPMagic(self::$query);
        return $magic_object;
    }
}

class OOPMagic{
    public $magic_count;
    public $magic_results;
    public $magic_record;
    
    function __construct($magicalArray = []) {
        $this->magic_count = $magicalArray['count'] ?? null;
        $this->magic_results = $magicalArray['results'] ?? null;
        $this->magic_record = $magicalArray['first'] ?? null;
        
        // Wanna return $this->magic_results as default if we ain't no callin' any methods from here
        return $this->magic_results;
    }
    
    public function makeMagic(){
    
        return 'Seems magic is ain`t no as magical as it would be.';
    }
    
    public function __call($method, $args)
	{
        //var_dump($method);
		if (!in_array($method, array_keys($this->functions))) {
            // it runs this function if we call a function whith absolutly no defined but we still have no any solutions to run the method without calling it from outside of this class (-_-)
			return $this->results();
		}

		array_unshift($args, $this->s);

		return call_user_func_array($this->functions[$method], $args);
	}
    
    public function results(){
        // run this query
        return 'I`m a default function'; // $this->magic_results;
    }
    
    public function count(){
        // run this query
        return $this->magic_count;
    }
}

$run = DB::from('users')->where('id', 3)->where('level', 2)->get();//->count();
dump($run);
