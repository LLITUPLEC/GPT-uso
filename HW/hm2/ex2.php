2. Как можно улучшить Singleton при помощи trait-ов


<?php

//Реализация синглтона через трейты. Следует обратить внимание, что трейт находится в неймспейсе Traits,
// а класс называется SingletonTrait (расположение файла допустим в Traits/SingletonTrait.php).

namespace Traits;

trait SingletonTrait
{
    /**
     * @var array список объектов
     */
    private static $instances = [];

    /**
     * @return self
     */
    public static function single()
    {
        if (!isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static;
        }

        return self::$instances[static::class];
    }
}
?>



<?php
//Создадим тестовый класс. Следует обратить внимание, тестовый класс находится в неймспейсе Classes,
// а название файла Test (расположение файла, допустим, в Classes/Test.php).

namespace Classes;

use Traits\SingletonTrait;

class Test
{
    use SingletonTrait;

    public $value = 0;
}

//После использования SingletonTrait в классе Test, можно использовать статический метод test().
?>



<?php
//Ниже пример использования. При помощи метода Test::single() создается и возвращается единственный экземпляр класса Test.

$a = \Classes\Test::single();
echo $a->value; // выведет 0

$a->value = 5;
echo $a->value; // выведет 5

$b = \Classes\Test::single();
echo $b->value; // выведет 5
?>