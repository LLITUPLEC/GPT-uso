1. Что выведет alert(typeof NaN); ?
'Number'


2. Что выведет alert(NaN === NaN); ?
'false'


3. 0.1 + 0.2 == 0.3 ?
'false' вычисленное значение будет равно 0.30000000000000004 != 0.3


4. Какой тип будет иметь переменная a, если она создается при помощи следующего кода:
var a = "a,b".split(',');
"Object"

5. Сделать так, чтобы при нажатии на элемент <а> алертом выводилось «Hello world!».
    можно повесить обработчик событий. для начала выберем элемент
    document.querySelector('.some-class' a) //выдаст ссылку элемент(ссылку) в классе some-class
    далее вешаем обработчик по клику
    document.querySelector('.some-class' a).addEventListener('click', alert("hello, world")) // ну или console.log вместо алерта


НИЖЕ решение на jQuery

6. Найти все элементы div с классом one, а также все элементы p с классом two.
   Затем добавить им всем класс three и визуально плавно спустить вниз.
   $("div.one").add("p.two").addClass("three").slideDown("slow")  // 2й способ Найти все элементы div с классом one = $( 'div').find('one');

7. Выбрать видимый div с именем red, который содержит тег span.
    $( "div[name=red]:visible:has(span)");


8. Привести пример замыкания.
    (function() {
    …
    })();
    Есть, правда, одна связанная с таким применением ловушка — внутри замыкания теряется значение слова this за его пределами. Решается она следующим образом:
    (function() {
    //вышестоящее this сохранится
    }).call(this);


9. Написать функцию, которая уменьшает или увеличивает указанное время на заданное количество минут, например:
    changeTime('10:00', 1) //return '10:01'
    changeTime('10:00', -1) //return '09:59'
    changeTime('23:59', 1) //return '00:00'
    changeTime('00:00', -1) //return '23:59'

    function timeIncrement(hours, min, interval){
    const newMin = (min + interval) % 60; // остаток от деления на 60
    const newHours = (hours + Math.floor((min + interval)/60)) % 24;  // Тут мы используем библиотечную функцию Math.floor для округления в меньшую сторону
    return `${newHours}:${newMin}`;  // составляем строку из полученных значений
    }

    // Проверяем:
    console.log(timeIncrement(11, 50, 40));
    console.log(timeIncrement(23, 30, 50));
    console.log(timeIncrement(10, 0, 320));

10. Написать функцию, возвращающую градус, на который указывают часовая и минутная стрелки в зависимости от времени, например:
    clock_degree("00:00") returns : "360:360"
    clock_degree("01:01") returns : "30:6"
    clock_degree("00:01") returns : "360:6"
    clock_degree("01:00") returns : "30:360"
    clock_degree("01:30") returns : "30:180"
    clock_degree("24:00") returns : "Check your time !"
    clock_degree("13:60") returns : "Check your time !"
    clock_degree("20:34") returns : "240:204"

11. Написать простую игру «Угадай число». Программа загадывает случайное число от 0 до 100.
    Игрок должен вводить предположения и получать ответы «Больше», «Меньше» или «Число угадано».
    Решение в отдельном файле "задание №11"
