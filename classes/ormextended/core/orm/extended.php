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
		if (array_key_exists('slug', $this->_object) && array_key_exists($this->slug_from, $this->_object) && !$this->slug) {
			$this->slug = SlugHelper::sluggify($this->{$this->slug_from});
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
	 * Override find_all to automatically order by 'order' column
	 * if present.
	 *
	 */
	public function find_all() {
		if (array_key_exists('order', $this->_object)) {
			$this->order_by('order');
		}
		return parent::find_all();
	}

}
