<?php
$min = 0;
$max = 1000;
$cars = [
    ['name' => 'Такси 1', 'position' => rand($min, $max), 'isFree' => (bool) rand(0, 1)],
    ['name' => 'Такси 2', 'position' => rand($min, $max), 'isFree' => (bool) rand(0, 1)],
    ['name' => 'Такси 3', 'position' => rand($min, $max), 'isFree' => (bool) rand(0, 1)],
    ['name' => 'Такси 4', 'position' => rand($min, $max), 'isFree' => (bool) rand(0, 1)],
    ['name' => 'Такси 5', 'position' => rand($min, $max), 'isFree' => (bool) rand(0, 1)],
];
$passenger = rand(0, 1000);


echo "пассажир находится на $passenger км";
echo "<br>";

for ($i = 0;$i < count($cars); $i++) {
    if ($cars[$i]['position'] < $passenger){
        $range[$i] = ['dist' => ($passenger - $cars[$i]['position']), 'name' => $cars[$i]['name'], 'isFree' => $cars[$i]['isFree']];
    } else if ($cars[$i]['position'] > $passenger) {
        $range[$i] = ['dist' => ($cars[$i]['position'] - $passenger), 'name' => $cars[$i]['name'],'isFree' => $cars[$i]['isFree']];
    } else if ($cars[$i]['position'] = $passenger) {
        $range[$i] = ['dist' => 0,'name' => $cars[$i]['name'],'isFree' => $cars[$i]['isFree']];
    }}

$arr_dist = [$max]; //исключаем ошибку пустого массива, если все такси заняты

//добавляем в массив значения дистанций ТОЛЬКО свободных такси
foreach ($range as $value) {
    if ($value['isFree'] == 1) {
        $arr_dist[] = $value['dist'];
    }

}
$min_dist = min($arr_dist); //задаёт переменной минимальное значение из массива всех дистанций до пассажира
foreach ($cars as $key => $value) {

    if ($value['position'] < $passenger){
        $range = $passenger - $value['position'];
    } else if ($value['position'] > $passenger) {
        $range = $value['position'] - $passenger;
    } else $range = 0;

    if (/* $value['isFree'] == 1 */$range == $min_dist) {
        $status = "(Свободен) - едет это такси";
    } else if ($value['isFree'] == 1) {
        $status = "(Свободен)";
    } else $status = "(Занят)";
    echo $value['name'] . " стоит на " . $value['position'] . " км, до пассажира " . $range . " км " . $status . "<br>";
}
?>