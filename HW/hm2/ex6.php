<?php
interface MyInt {
    public function funcI();
    private function funcP();//не может быть приватным
}
class A {
    protected prop1;//не обозначена как переменная "$"
    public prop2;//не обозначена как переменная "$"

    function funcA(){
        return $this->prop2;//поэтому здесь и не видит это свойство
    }
}
class B extends A {
    function funcB(){
        return $this->prop1;
    }
}
class C extends B implements MyInt { //Класс должен быть объявлен как абстрактный или реализовать метод 'funcI'
    function funcB(){
        return $this->prop1;
    }
    private function funcP(){//соответственно здесь и не видит метод
        return 123;
    }
}
$b = new B();//тоже без скобок я бы написал
$b->funcA();
$c = new C();//без скобок наверно нужно
$c->funcI();
