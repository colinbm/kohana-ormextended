ORM Extended
============

Drop this module in and it adds some tasty ORM extras. These are:


Injected Relations
------------------

Add a relation to a model without predefining it. Useful in optional modules, for instance in init.php for a Blog module:

```php
<?php
ORM::inject_relation('Model_User', '_has_one', array('blog' => array('model' => 'blog'))))
```


Slug Generation
---------------

Define a column in your model's table called `slug` and this will be automatically filled with a sluggified version of `$slug_from` (default `name`) on save.


Automatic Timestamps
--------------------

Just add `created_at` and `updated_at` columns, and they'll be automatically updated as you'd expect.


Custom Getters
--------------

Define a method with a `get_` prefix, e.g. `get_full_name()`, and you can access it as `$object->full_name`. e.g.:

```php
<?php
public function get_full_name() {
	return $this->first_name . ' ' . $this->last_name;
}
```


Default Ordering
----------------

To set a default order when using the model's factory, just set `$_order` and optionally `$_order_dir`.

```php
<?php
protected $_order = 'created_at';
protected $_order_dir = 'desc';
```

Alternatively you can just include an `order` column and this will automatically be used.

If you need to override the default order, you can use `ORM::factory('model')->reset_order_by()->…`


IN / NOT IN Helpers
-------------------

```php
<?php
ORM::factory('user')->where('id', 'IN', $users);
```

If `$users` is an empty array in the above code, by default it generates the following invalid SQL:

```sql
SELECT * FROM users WHERE ID IN ()
```

But it's obvious what we mean. In this event it should return no matches. So we identify use of `IN ()` or `NOT IN ()`, and change them to `1=0` and `1=1` respectively.


Join With Latest
----------------

Sometimes we want to just get the latest associated record. e.g. if we have a Page model, which has multiple PageRevisions. Normally we want the latest PageRevision's content. We can now do:

```php
<?php
ORM::factory('page')->join_with_latest('pagerevisions', 'page_id')
```
