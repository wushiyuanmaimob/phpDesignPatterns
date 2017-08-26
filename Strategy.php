<?php
    interface IStrategy
    {
        function filter($record);
    }

    class FindAfterStrategy implements IStrategy
    {
        private $_name;

        public function filter($record)
        {
            // TODO: Implement filter() method.
            return strcmp($this->_name, $record) <= 0;
        }
    }

    class RandomStrategy implements IStrategy
    {
        public function filter($record)
        {
            // TODO: Implement filter() method.
            return rand(0, 1) >= 0.5;
        }
    }

    class UserList
    {
        private $_list = [];

        public function __construct($names)
        {
            if(is_array($names) && !empty($names)) {
                foreach ($names as $name) {
                    $this->_list[] = $name;
                }
            }
        }

        public function add($name)
        {
            $this->_list[] = $name;
        }

        public function find($filter)
        {
            $res = [];
            foreach ($this->_list as $user) {
                if($filter->filter($user)) {
                    $res[] = $user;
                }
            }
            return $res;
        }
    }

    $ul = new UserList([
        'Andy',
        'Jack',
        'Lori',
        'Megan'
    ]);

    $f1 = $ul->find(new FindAfterStrategy('J'));
    print_r($f1);