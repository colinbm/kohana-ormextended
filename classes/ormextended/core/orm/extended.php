<?php defined('SYSPATH') or die('No direct script access.');

class OrmExtended_Core_ORM_Extended extends Kohana_ORM {

	/**
	 * Array holding injected relations.
	 * @var array $injected_relations
	 */
	public static $injected_relations = array();

	/**
	 * Add a relation to the model dynamically
	 *
	 * @param string $model
	 * @param string $type
	 * @param array $relation
	 */
	public static function inject_relation($model, $type, $relation) {
		if (!isset(self::$injected_relations[$model])) self::$injected_relations[$model] = array();
		if (!isset(self::$injected_relations[$model][$type])) self::$injected_relations[$model][$type] = array();
		self::$injected_relations[$model][$type] = self::$injected_relations[$model][$type] + $relation;
	}

	/**
	 * Override __constructor to add any injected relations at instantiation
	 *
	 */
	public function __construct($id = NULL) {
		if (isset(self::$injected_relations[get_class($this)])) {
			foreach(self::$injected_relations[get_class($this)] as $type => $relation) {
				$this->$type = $this->$type + $relation;
			}
		}
		parent::__construct($id);
	}


	/**
	 * Column to use for slug generation
	 *
	 * @var string $slug_from
	 */
	protected $slug_from = 'name';

	/**
	 * Override save to generate slug if none is provided
	 */
	public function save(Validation $validation = NULL) {
		if (array_key_exists('slug', $this->_object) && (array_key_exists($this->slug_from, $this->_object) || method_exists($this, 'get_'.$this->slug_from))) {
			if ($this->slug) {
				$this->slug = SlugHelper::sluggify($this->slug);
			} else {
				$this->slug = SlugHelper::sluggify($this->{$this->slug_from});
			}
		}

		if (array_key_exists('order', $this->_object) && !$this->order) {
			$class   = get_class($this);
			$factory = new $class;
			$this->order = $factory->order_by('order', 'desc')->limit(1)->find()->order+1;
		}

		return parent::save($validation);
	}

	/**
	 * Override _initialize to activate timestamp columns.
	 */
	public function _initialize() {
		parent::_initialize();

		if (array_key_exists('updated_at', $this->_object)) {
			$this->_updated_column = array(
				'column' => 'updated_at',
				'format' => 'Y-m-d H:i:s',
			);
		}

		if (array_key_exists('created_at', $this->_object)) {
			$this->_created_column = array(
				'column' => 'created_at',
				'format' => 'Y-m-d H:i:s',
			);
		}
	}

	/**
	 * Allow get_* methods to be accessed as $obj->*
	 *
	 * @param string $column
	 * @return mixed
	 */
	public function __get($column) {
		$get_method = 'get_'.$column;
		if (method_exists($this, $get_method)) {
			return $this->$get_method();
		} else {
			return parent::__get($column);
		}
	}

	/**
	 * Column to use for order_by
	 *
	 * @var string $_order
	 */
	protected $_order = 'order';
	
	/**
	 * Override find_all to automatically order by $this->_order column
	 * if present.
	 *
	 */	
	public function find_all() {
		if (array_key_exists($this->_order, $this->_object)) {
			$this->order_by($this->_order);
		}
		return parent::find_all();
	}
	
	/**
	 * Remove any ordering. Useful if it's been set automatically
	 * in the __construct.
	 *
	 */
	public function reset_order_by() {
		foreach($this->_db_pending as $index => $part) {
			if ($part['name'] == 'order_by') {
				unset($this->_db_pending[$index]);
			}
		}
		return $this;
	}
	
	/**
	 * If you use IN or NOT IN and pass an empty array, this results in invalid SQL ("IN ()")
	 * Change the query in these instances as follows:
	 *    Original   Intent               Result
	 *    IN ()      return no results    1=0
	 *    NOT IN ()  return all results   1=1
	 */
	public function where($column, $op, $value)     { return $this->custom_where('where', $column, $op, $value); }
	public function and_where($column, $op, $value) { return $this->custom_where('and_where', $column, $op, $value); }
	public function or_where($column, $op, $value)  { return $this->custom_where('or_where', $column, $op, $value); }
	
	protected function custom_where($type, $column, $op, $value) {
		if (in_array($op, array('IN', 'NOT IN')) && !$value) {
			return parent::$type(DB::expr(1), '=', $op == 'IN' ? DB::expr(0) : DB::expr(1));
		}
		return parent::$type($column, $op, $value);
	}
	
	/**
	 * Join with a has_many relationship, getting the most recent entry only.
	 * See http://stackoverflow.com/questions/2111384/sql-join-selecting-the-last-records-in-a-one-to-many-relationship
	 */
	public function join_with_latest($table, $foreign_key, $timestamp_column='created_at', $id_column='id') {
		if (!is_array($table)) $table = array($table, $table);
		
		$model = strtolower(str_replace('Model_', '', get_class($this)));
		return $this
			->join($table)->on("{$model}.{$id_column}", '=', "{$table[1]}.{$foreign_key}")
			->join(array($table[0], 't2'), 'left')
			->on("{$model}.{$id_column}", '', DB::expr("=t2.{$foreign_key} AND {$table[1]}.{$timestamp_column}<t2.{$timestamp_column} OR ({$table[1]}.{$timestamp_column}=t2.{$timestamp_column} AND {$table[1]}.{$id_column}<t2.{$id_column})"))
			->where("t2.{$id_column}", 'IS', null);
	}

}
